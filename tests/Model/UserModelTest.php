<?php declare(strict_types=1);

namespace App\Tests\Model;

use App\Model\UserModel;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Utils\Andresmei\FlashResponse;
use App\Entity\User;
use App\Tests\TestTrait;

class UserModelTest extends TestCase
{
    use TestTrait;

    private $fileExists;
    private $isDir;

    protected function setUp()
    {
        $this->fileExists = $this->getMockBuilder(\stdClass::class)
            ->setMethods(['file_exists'])
            ->getMock();
        $this->isDir = $this->getMockBuilder(\stdClass::class)
            ->setMethods(['is_dir'])
            ->getMock();
    }

    public function testAddUser()
    {
        $em = $this->getTestManager();
        $encoder = $this->getTestEncoder();
        $model = new UserModel($em);
        $image = tempnam(sys_get_temp_dir(), 'upl');
        imagepng(imagecreatetruecolor(10, 10), $image);
        $file = new UploadedFile($image, 'fake_image.png');
        $data = array(
            'email' => 'andre2meireles@gmail.com',
            'password' => '12345678',
            'retype' => '12345678',
            'userNickname' => 'fred'
        );
        $result = $model->addUser($data, $file, $encoder);

        $this->assertEquals(new FlashResponse(200, 'success', 'Alteração das informações feita com sucesso!'), $result);
    }

    public function testEditUser()
    {
        $em = $this->getTestManager(new User());
        $encoder = $this->getTestEncoder();
        $model = new UserModel($em);
        $file = $this->getTestImage();
        $data = array(
            'identificator' => 1,
            'email' => 'andre2meireles@gmail.com',
            'password' => '12345678',
            'retype' => '12345678',
            'userNickname' => 'fred'
        );
        $result = $model->editUser($data, $file, $encoder, true);

        $this->assertEquals(new FlashResponse(200, 'success', 'Alteração das informações feita com sucesso!'), $result);
    }

    public function testChangePassword()
    {
        $model = new UserModel($this->getTestManager(new User));
        $user = $this->getTestUser();
        $data = array(
            'oldpass' => '123',
            'pass' => '12345678',
            'retype' => '12345678'
        );
        $result = $model->changePassword($user, $data, $this->getTestEncoder());

        $this->assertEquals(new FlashResponse(200, 'success', 'Senha alterada com sucesso!'), $result);
    }

    public function testUploadProfileImage()
    {
        $model = new UserModel($this->getTestManager(new User));
        $user = $this->getTestUser();
        $image = $this->getTestImage();

        $result = $model->uploadProfileImage($user, $image);

        $this->assertEquals($model, $result);
    }

    public function testResetProfileImage()
    {
        $model = new UserModel($this->getTestManager());
        /** @var User $user */
        $user = $this->getTestUser();
        $user->setProfileImage('test.jpg');
                
        $user->expects($this->any())
            ->method('getProfileImage')
            ->willReturn('test.jpg');
        
        $this->fileExists->expects($this->any())
            ->willReturn(true)
            ->method('file_exists');

        $this->isDir->expects($this->any())
            ->willReturn(false)
            ->method('is_dir');

        $result = $model->resetProfileImage(__DIR__.'/../../public/uploads/images/profile', $user);

        $this->assertEquals(new FlashResponse(200, 'success', 'Imagem resetada com sucesso!'), $result);
    }
}
