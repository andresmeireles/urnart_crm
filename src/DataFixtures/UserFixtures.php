<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('fred@ninja');
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            '123'
        ));
        $user->setUserNickname('Fred');
        $user->setRoles(['ROLE_ADMIN', 'ROLE_SUPER_ADMIN']);

        $manager->persist($user);
        $manager->flush();
    }
}
