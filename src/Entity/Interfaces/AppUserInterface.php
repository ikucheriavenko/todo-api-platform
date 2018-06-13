<?php
/**
 * Created by PhpStorm.
 * User: Ivan Kucheriavenko
 * Date: 6/15/18
 * Time: 11:36 AM
 */

namespace App\Entity\Interfaces;

use Symfony\Component\Security\Core\User\UserInterface;

interface AppUserInterface extends UserInterface
{
    /**
     * @return string
     */
    public function getUsername(): ?string;

    /**
     * @param string $username
     * @return AppUserInterface
     */
    public function setUsername(?string $username): AppUserInterface;

    /**
     * @return string
     */
    public function getPassword(): ?string;


    /**
     * @param string $password
     * @return AppUserInterface
     */
    public function setPassword(?string $password): AppUserInterface;
}