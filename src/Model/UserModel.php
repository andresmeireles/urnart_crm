<?php

namespace App\Model;

use App\Entity\User;
use App\Utils\Andresmei\FlashResponse;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class UserModel extends Model
{
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
