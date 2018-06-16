<?php
/**
 * Created by PhpStorm.
 * User: Ivan Kucheriavenko
 * Date: 6/16/18
 * Time: 1:30 PM
 */

namespace App\Tests\Manager;

use App\Dto\User\AuthUser;
use App\Entity\User;
use App\Manager\AppUserManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class AppUserManagerTest extends TestCase
{
    /** @var  EntityManagerInterface */
    private $em;
    /** @var EncoderFactoryInterface */
    private $encoderFactory;

    private $userClass = User::class;

    protected function setUp()
    {
        $this->em = $this->createMock(EntityManagerInterface::class);
        $this->encoderFactory = $this->createMock(EncoderFactoryInterface::class);
    }

    public function testRegisterUser()
    {
        $username = 'foo';
        $pass = 'bar';
        $passHash = 'testHash';
        $authUser = (new AuthUser())
            ->setUsername($username)
            ->setPassword($pass);

        $userManager = $this
            ->getMockBuilder(AppUserManager::class)
            ->setConstructorArgs([$this->em, $this->encoderFactory, $this->userClass])
            ->setMethods(['hashPassword'])
            ->getMock();

        $userManager
            ->expects($this->once())
            ->method('hashPassword')
            ->with($pass)
            ->willReturn($passHash);

        $this->em
            ->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf($this->userClass));
        $this->em
            ->expects($this->once())
            ->method('flush');

        $this->assertInstanceOf($this->userClass, $userManager->registerUser($authUser));
    }

    public function testFindUserByUsername()
    {
        $username = 'test';

        $repository = $this->createMock(ObjectRepository::class);

        $this->em
            ->expects($this->once())
            ->method('getRepository')
            ->with($this->userClass)
            ->willReturn($repository);

        $repository
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['username' => $username]);

        $userManager = new AppUserManager($this->em, $this->encoderFactory, $this->userClass);
        $userManager->findUserByUsername($username);
    }

    public function testHashPassword()
    {
        $pass = 'test';
        $passHash = 'testHash';

        $this->encoderFactory
            ->expects($this->once())
            ->method('getEncoder')
            ->with($this->userClass)
            ->willReturn($encoder = $this->createMock(PasswordEncoderInterface::class));

        $encoder
            ->expects($this->once())
            ->method('encodePassword')
            ->with($pass, null)
            ->willReturn($passHash);

        $userManager = new AppUserManager($this->em, $this->encoderFactory, $this->userClass);
        $this->assertEquals($passHash, $userManager->hashPassword($pass));
    }

    public function testCreateUser()
    {
        $userManager = new AppUserManager($this->em, $this->encoderFactory, $this->userClass);
        $this->assertInstanceOf($this->userClass, $userManager->createUser());
    }

    public function testGetClass()
    {
        $userManager = new AppUserManager($this->em, $this->encoderFactory, $this->userClass);
        $this->assertEquals($this->userClass, $userManager->getClass());
    }
}