<?php
/**
 * Created by PhpStorm.
 * User: Ivan Kucheriavenko
 * Date: 13.06.18
 * Time: 22:31
 */

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Interfaces\AppUserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ApiResource(
 *     collectionOperations={},
 *     itemOperations={
 *         "get"={"method"="GET"},
 *     },
 *     attributes={
 *         "normalization_context"={
 *              "groups"={"user", "user-read"},
 *              "swagger_definition_name": "READ",
 *          },
 *         "denormalization_context"={
 *              "groups"={"user", "user-write"},
 *              "swagger_definition_name": "WRITE",
 *          },
 *     }
 * )
 */
class User implements AppUserInterface
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Groups({"user"})
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $username;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Task", mappedBy="owner", orphanRemoval=true)
     */
    protected $tasks;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getUsername() ?? 'New user';
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return AppUserInterface
     */
    public function setUsername(?string $username): AppUserInterface
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return AppUserInterface
     */
    public function setPassword(?string $password): AppUserInterface
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returns the roles granted to the user.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials(): void
    {
    }
}
