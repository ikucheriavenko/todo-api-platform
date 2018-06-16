<?php
/**
 * Created by PhpStorm.
 * User: Ivan Kucheriavenko
 * Date: 6/15/18
 * Time: 11:24 AM.
 */

declare(strict_types=1);

namespace App\Manager;

use App\Dto\User\AuthUser;
use App\Entity\Interfaces\AppUserInterface;

interface AppUserManagerInterface
{
    /**
     * Creates an empty user instance.
     *
     * @return AppUserInterface
     */
    public function createUser(): AppUserInterface;

    /**
     * Returns the user's fully qualified class name.
     *
     * @return string
     */
    public function getClass(): string;

    /**
     * Find a user by its username.
     *
     * @param string $username
     *
     * @return AppUserInterface
     */
    public function findUserByUsername(?string $username): ?AppUserInterface;

    /**
     * @param AuthUser $dto
     *
     * @return AppUserInterface
     */
    public function registerUser(AuthUser $dto): AppUserInterface;

    /**
     * @param string $password
     *
     * @return string
     */
    public function hashPassword(string $password): string;
}
