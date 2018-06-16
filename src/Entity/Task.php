<?php
/**
 * Created by PhpStorm.
 * User: Ivan Kucheriavenko
 * Date: 13.06.18
 * Time: 22:31.
 */

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ApiResource(
 *     collectionOperations={
 *         "get"={
 *             "method"="GET"
 *         },
 *         "post"={
 *             "method"="POST"
 *         },
 *     },
 *     itemOperations={
 *         "get"={"method"="GET"},
 *         "put"={
 *             "method"="PUT",
 *             "access_control"="object.getOwner() == user",
 *             "access_control_message"="Sorry, but can't edit this task"
 *         },
 *         "delete"= {
 *              "method"="DELETE",
 *              "access_control"="object.getOwner() == user",
 *              "access_control_message"="Sorry, but can't delete this task"
 *         }
 *     },
 *     attributes={
 *         "normalization_context"={
 *              "groups"={"task", "task-read"},
 *              "swagger_definition_name": "READ",
 *          },
 *         "denormalization_context"={
 *              "groups"={"task", "task-write"},
 *              "swagger_definition_name": "WRITE",
 *         },
 *     },
 * )
 */
class Task
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Groups({"task"})
     *
     * @Assert\NotNull
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $content;

    /**
     * @Groups({"task-read"})
     *
     * @Gedmo\Timestampable(on="create")
     *
     * @ORM\Column(type="datetime")
     *
     * @ApiProperty(
     *     attributes={
     *         "swagger_context"={
     *             "type"="datetime"
     *         }
     *     }
     * )
     */
    protected $createdAt;

    /**
     * @Groups({"task"})
     *
     * @Assert\NotNull
     *
     * @ORM\Column(type="boolean")
     */
    protected $completed;

    /**
     * @var User
     *
     * @Groups({"task-read"})
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="tasks")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $owner;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getContent() ?? '';
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
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return Task
     */
    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return bool
     */
    public function getCompleted(): ?bool
    {
        return $this->completed;
    }

    /**
     * @param bool $completed
     *
     * @return Task
     */
    public function setCompleted(?bool $completed): self
    {
        $this->completed = $completed;

        return $this;
    }

    /**
     * @return User
     */
    public function getOwner(): ?User
    {
        return $this->owner;
    }

    /**
     * @param User $owner
     *
     * @return Task
     */
    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}
