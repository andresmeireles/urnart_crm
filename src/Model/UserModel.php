<?php

namespace App\Model;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Utils\Andresmei\FlashResponse;
use App\Utils\Exceptions\NotSamePasswordException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UserModel extends Model
{
    public function runUserAction(array $data, ?UploadedFile $file, ?UserPasswordEncoderInterface $encoder = null, bool $insert = true): FlashResponse
    {
        $entityManager = $this->em;

        $entityManager->getConnection()->beginTransaction();

        try {
            // check if password is the same
            if (array_key_exists('password', $data) && $data['password'] !== $data['retype']) {
                throw new NotSamePasswordException();
            }

            $user =  $insert ? new User() : $entityManager->getRepository(User::class)->find($data['identificator']);
            $user->setEmail($data['email']);
            $user->setUserNickname($data['userNickname']);

            if (null !== $encoder) {
                $user->setPassword($encoder->encodePassword($user, $data['password']));
            }

            //image
            if ($file !== null) {
                /* $user->setProfileImageFile($file);
                $imageName = sprintf('%s-profile.%s', $data['userNickName'], $file->getExtension());
                $user->setProfileImage($imageName); */
                $this->uploadProfileImage($user, $file);
            }

            $entityManager->persist($user);
            $entityManager->flush();
            $entityManager->getConnection()->commit();
        } catch (UniqueConstraintViolationException $e) {
            $entityManager->getConnection()->rollback();
            throw new \Exception('Email já cadastrado.');
        } catch (\PDOException $e) {
            $entityManager->getConnection()->rollback();
            throw new \Exception($e->getMessage());
        }

        return new FlashResponse(200, 'success', 'Alteração das informações feita com sucesso!');
    }

    public function addUser(array $data, ?UploadedFile $file, UserPasswordEncoderInterface $encoder): FlashResponse
    {
        return $this->runUserAction($data, $file, $encoder);
    }

    public function editUser(array $data, ?UploadedFile $file): FlashResponse
    {
        return $this->runUserAction($data, $file, null, false);
    }

    public function uploadProfileImage(User $user, UploadedFile $imageFile)
    {
        $entityManager = $this->em;

        $user->setProfileImageFile($imageFile);
        $imageName = sprintf('%s-profile.%s', $user->getUserNickname(), $imageFile->getExtension());
        $user->setProfileImage($imageName);

        $entityManager->persist($user);
        $entityManager->flush();
    }

    public function resetProfileImage(string $profileImagesFolder, User $user): FlashResponse
    {
        $imageFullPath = sprintf('%s/%s', $profileImagesFolder, $user->getProfileImage());
        if (!file_exists($imageFullPath) || is_dir($imageFullPath)) {
            return new FlashResponse(200, 'error', 'Usuario não tem imagem definida para ser resetada.');
        }

        $user->setProfileImage(null);

        $this->em->merge($user);
        $this->em->flush();

        unlink($imageFullPath);

        return new FlashResponse(200, 'success', 'Imagem resetada com sucesso!');
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
