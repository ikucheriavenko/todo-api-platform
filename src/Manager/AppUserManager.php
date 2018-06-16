<?php
/**
 * Created by PhpStorm.
 * User: Ivan Kucheriavenko
 * Date: 6/15/18
 * Time: 11:27 AM.
 */

declare(strict_types=1);

namespace App\Manager;

use App\Dto\User\AuthUser;
use App\Entity\Interfaces\AppUserInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class AppUserManager implements AppUserManagerInterface
{
    private $em;
    private $encoderFactory;
    private $class;

    public function __construct(EntityManagerInterface $em, EncoderFactoryInterface $encoderFactory, string $class)
    {
        $this->em = $em;
        $this->encoderFactory = $encoderFactory;
        $this->class = $class;
    }

    /**
     * @param AuthUser $dto
     *
     * @return AppUserInterface
     */
    public function registerUser(AuthUser $dto): AppUserInterface
    {
        $user = $this->createUser()
            ->setUsername($dto->getUsername())
            ->setPassword($this->hashPassword($dto->getPassword()));

        $this->em->persist($user);
        $this->em->flush($user);

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function findUserByUsername(?string $username): ?AppUserInterface
    {
        return $this->em->getRepository($this->class)->findOneBy([
            'username' => $username,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function hashPassword(string $password): string
    {
        return $this->encoderFactory
            ->getEncoder($this->class)
            ->encodePassword($password, null);
    }

    /**
     * {@inheritdoc}
     */
    public function createUser(): AppUserInterface
    {
        $class = $this->getClass();

        return new $class();
    }

    /**
     * {@inheritdoc}
     */
    public function getClass(): string
    {
        return $this->class;
    }
}
