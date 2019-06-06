<?php

namespace App\Model;

use App\Entity\User;
use App\Utils\Andresmei\FlashResponse;
use App\Utils\Exceptions\NotSamePasswordException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class UserModel extends Model
{
    /**
     * Ação faz ação na tabela user do banco de dados
     *
     * @param array                             $data    Dados para alteração
     * @param UploadedFile|null                 $file    Instancia de UploadFile
     * @param UserPasswordEncoderInterface|null $encoder Endoder do password
     * @param bool                              $insert  Tipo de inserção
     *
     * @return FlashResponse                              Objeto de resposta
     */
    public function runUserAction(
        array $data,
        ?UploadedFile $file,
        ?UserPasswordEncoderInterface $encoder = null,
        bool $insert = true
    ): FlashResponse {
        $entityManager = $this->em;

        try {
            // check if password is the same
            if (array_key_exists('password', $data)) {
                $this->passwordVerification($data['password'], $data['retype']);
            }

            $user =  $insert ? new User() : $entityManager->getRepository(User::class)->find($data['identificator']);
            $user->setEmail($data['email']);
            $user->setUserNickname($data['userNickname']);

            if (null !== $encoder) {
                $password = $encoder->encodePassword($user, $data['password']);
                $user->setPassword($password);
            }

            //image
            if ($file !== null) {
                /* $user->setProfileImageFile($file);
                $imageName = sprintf('%s-profile.%s', $data['userNickName'], $file->getExtension());
                $user->setProfileImage($imageName); */
                $this->uploadProfileImage($user, $file);
            }

            $entityManager->persist($user);
        } catch (UniqueConstraintViolationException $e) {
            throw new \Exception('Email já cadastrado.');
        } catch (\PDOException $e) {
            throw new \Exception($e->getMessage());
        }

        $entityManager->flush();

        return new FlashResponse(200, 'success', 'Alteração das informações feita com sucesso!');
    }

    /**
     * Alias to runUser action with add command
     *
     * @param array                        $data    Dados do usuario
     * @param UploadedFile|null            $file    Arquivo da imagem
     * @param UserPasswordEncoderInterface $encoder Encoder de senha
     *
     * @return FlashResponse                           [return description]
     */
    public function addUser(array $data, ?UploadedFile $file, UserPasswordEncoderInterface $encoder): FlashResponse
    {
        return $this->runUserAction($data, $file, $encoder);
    }

    public function editUser(array $data, ?UploadedFile $file): FlashResponse
    {
        return $this->runUserAction($data, $file, null, false);
    }

    /**
     * Altera senha do usuario.
     *
     * @param   User                          $user     Objeto contendo informações do usuario.
     * @param   array                         $data     Informações para alterar senhas
     * @param   UserPasswordEncoderInterface  $encoder  Encoder de para senha
     *
     * @return  FlashResponse
     */
    public function changePassword(User $user, array $data, UserPasswordEncoderInterface $encoder): FlashResponse
    {
        $oldTypedPass = $data['oldpass'];
        $newPass = $data['pass'];
        $retype = $data['retype'];

        if (!$encoder->isPasswordValid($user, $oldTypedPass)) {
            return new FlashResponse(200, 'error', 'Senha atual incorreta');
        }

        if ($oldTypedPass === $newPass) {
            return new FlashResponse(200, 'error', 'Para alterar senha insira uma senha diferente da anterior.');
        }

        $this->passwordVerification($newPass, $retype);

        $user->setPassword($encoder->encodePassword($user, $newPass));
        
        $this->em->merge($user);
        $this->em->flush();

        return new FlashResponse(200, 'success', 'Senha alterada com sucesso!');
    }

    /**
     * Upload profile image using vich upload system
     *
     * @param User         $user      User entity
     * @param UploadedFile $imageFile File to upload.
     *
     * @return UserModel
     */
    public function uploadProfileImage(User $user, UploadedFile $imageFile): self
    {
        $entityManager = $this->em;

        $user->setProfileImageFile($imageFile);
        $imageName = sprintf('%s-profile.%s', $user->getUserNickname(), $imageFile->getExtension());
        $user->setProfileImage($imageName);

        $entityManager->persist($user);
        $entityManager->flush();

        return $this;
    }

    public function resetProfileImage(string $profileImagesFolder, User $user): FlashResponse
    {
        $imageFullPath = sprintf('%s/%s', $profileImagesFolder, $user->getProfileImage());
        if (!file_exists($imageFullPath)) {
            return new FlashResponse(200, 'error', 'Usuario não tem imagem definida para ser resetada.');
        }
        if (is_dir($imageFullPath)) {
            return new FlashResponse(302, 'error', 'É diretorio.');
        }

        $user->setProfileImage(null);

        $this->em->merge($user);
        $this->em->flush();

        if (getenv('APP_ENV') !== 'test') {
            unlink($imageFullPath);
        }

        return new FlashResponse(200, 'success', 'Imagem resetada com sucesso!');
    }

    public function editRoles(int $userId, string $userName, array $roles): FlashResponse
    {
        $entityManager = $this->em;

        $user = $this->em->getRepository(User::class)->findOneBy(
            ['id' => $userId, 'email' => $userName]
        );

        if (null === $user) {
            throw new UsernameNotFoundException(sprintf('Usuario %s não encontrado ou não existe', $userName));
        }

        $user->setRoles($roles);

        $entityManager->merge($user);
        $entityManager->flush();

        return new FlashResponse(200, 'success', sprintf('Permissões de %s alterados com sucesso', $user->getUserName()));
    }

    private function passwordVerification(string $pass1, string $pass2): self
    {
        if ($pass1 !== $pass2) {
            throw new NotSamePasswordException();
        }

        if (strlen($pass1) < 8) {
            throw new \Exception("Senha não atende os parametros requisitados. Senha precisa ter no minímo 8 digitos.");
        }

        return $this;
    }
}
