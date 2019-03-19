<?php

namespace App\Model;

use App\Entity\User;
use App\Utils\Andresmei\FlashResponse;
use App\Utils\Exceptions\NotSamePasswordException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserModel extends Model
{
    public function addUser(array $data, UserPasswordEncoderInterface $encoder): FlashResponse
    {
        $entityManager = $this->em;

        $entityManager->getConnection()->beginTransaction();

        try {

            // check if password is the same
            if ($data['password'] !== $data['retype']) {
                throw new NotSamePasswordException();
            } 

            $user = new User();
            $user->setEmail($data['email']);
            $user->setUserNickname($data['userNickName']);
            $user->setPassword($encoder->encodePassword($user, $data['password']));

            $entityManager->persist($user);
            $entityManager->flush();
            $entityManager->getConnection()->commit();
        } catch (UniqueConstraintViolationException $e) {
            $entityManager->getConnection()->rollback();
            throw new \Exception ('Email já cadastrado.');
        } catch (\PDOException $e) {
            $entityManager->getConnection()->rollback();
            throw new \Exception($e->getMessage());
        }

        return new FlashResponse(200, 'success', 'sucesso meu amigo!');
    }

    public function editRoles(int $userId, string $userName, array $roles): FlashResponse
    {
        $entityManager = $this->em;
        
        $user = $this->em->getRepository(User::class)->findOneBy(array(
            'id' => $userId,
            'email' => $userName
        ));

        if (null === $user) {
            throw new UsernameNotFoundException(sprintf('Usuario %s não encontrado ou não existe', $userName));
        }

        $user->setRoles($roles);

        $entityManager->merge($user);
        $entityManager->flush();

        return new FlashResponse(200, 'success', sprintf('Permissões de %s alterados com sucesso', $user->getUserName()));
    }
}
