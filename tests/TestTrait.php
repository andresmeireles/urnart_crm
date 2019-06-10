<?php
declare(strict_types=1);

namespace App\Tests;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Persistence\ObjectRepository;
use App\Entity\User;
use Doctrine\ORM\EntityManager;

/**
 * Functions to use on tests.
 */
trait TestTrait
{
    /**
     * @return EntityManager
     */
    public function getTestEntityManager(object $entity = null): EntityManager
    {
        $entityManager = $this->createMock(EntityManager::class);
        $entityManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($this->getTestRepository($entity));

        return $entityManager;
    }

    /**
     * @return ObjectManager
     */
    public function getTestManager(Object $entity = null): ObjectManager
    {
        $objectManager = $this->createMock(ObjectManager::class);
        
        $objectManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($this->getTestRepository($entity));
        
        return $objectManager;
    }

    public function getTestRepository(?object $entity = null)
    {
        $repository = $this->createMock(ObjectRepository::class);
        $repository->expects($this->any())
            ->method('find')
            ->willReturn($entity);
        $repository->expects($this->any())
            ->method('findAll')
            ->willReturn([]);
        $repository->expects($this->any())
            ->method('findBy')
            ->willReturn([]);
        $repository->expects($this->any())
            ->method('findOneBy')
            ->willReturn($entity);

        return $repository;
    }

    public function getTestUser()
    {
        $user = $this->createMock(User::class);

        return $user;
    }

    public function getTestEncoder(bool $isValid = true)
    {
        $encoder = $this->createMock(UserPasswordEncoderInterface::class);
        /**
         * Para fazer o mock functionar
         * https://github.com/doctrine/DoctrineMigrationsBundle/issues/221 
         */
        $encoder->expects($this->any())
            ->method('encodePassword')
            ->willReturn('overload');
        
        $encoder->expects($this->any())
            ->method('isPasswordValid')
            ->willReturn($isValid);
                
        return $encoder;
    }

    public function getTestModel(string $modelName, string $entityName)
    {
        $model = ucwords($modelName);
        $entity = ucwords($entityName);
        $model = new $model($this->getTestManager(new $entity));
    }

    public function getTestImage()
    {
        $image = tempnam(sys_get_temp_dir(), 'upl');
        imagepng(imagecreatetruecolor(10,10), $image);
        $file = new UploadedFile($image, 'fake_image.png'); 
        return $file;
    }
}
