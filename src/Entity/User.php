<?php declare(strict_types = 1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\UserInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @Vich\Uploadable
 */
class User extends BaseEntity implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     *
     * @var  string Nome do usuário.
     */
    private $userNickname;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     *
     * @var string Email do usuário.
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     *
     * @var array Permissões do usuário.
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string")
     *
     * @var string The hashed password
     */
    private $password;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string|null Name of image. Possible hashed.
     */
    private $profileImage;

    /**
     * @Vich\UploadableField(mapping="user_prodile_images", fileNameProperty="profileImage")
     * @var File|null
     */
    private $profileImageFile;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserNickname(): ?string
    {
        return $this->userNickname;
    }

    public function setUserNickname(string $userNickname): self
    {
        $this->userNickname = $userNickname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getProfileImageFile(): ?File
    {
        return $this->profileImageFile;
    }

    public function setProfileImageFile(?File $imageFile = null): self
    {
        $this->profileImageFile = $imageFile;

        if (null !== $imageFile) {
            $this->setLastUpdate();
        }
        
        return $this;
    }

    public function getProfileImage(): ?string
    {
        return $this->profileImage;
    }

    public function setProfileImage(?string $image): self
    {
        $this->profileImage = $image;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * Para não criar conflito com o vich uploader é
     * nescessário sobreescrever os metodos serizalização
     * e unserialização
     */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->email,
            $this->password,
        ]);
    }

    public function unserialize($serialized)
    {
        [
            $this->id,
            $this->email,
            $this->password,
        ] = unserialize($serialized);
    }
}
