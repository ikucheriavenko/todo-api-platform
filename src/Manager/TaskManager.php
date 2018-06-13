<?php
/**
 * Created by PhpStorm.
 * User: Ivan Kucheriavenko
 * Date: 6/16/18
 * Time: 11:35 AM
 */

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Task;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class TaskManager
 * @package App\Manager
 */
class TaskManager implements TaskManagerInterface
{
    private $tokenStorage;

    /**
     * TaskManager constructor.
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @inheritdoc
     */
    public function setOwner(Task $task): Task
    {
        if ($token = $this->tokenStorage->getToken()) {
            $task->setOwner($token->getUser());
        }

        return $task;
    }
}