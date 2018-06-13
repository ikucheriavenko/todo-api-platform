<?php
/**
 * Created by PhpStorm.
 * User: Ivan Kucheriavenko
 * Date: 6/16/18
 * Time: 11:28 AM
 */

declare(strict_types=1);

namespace App\Doctrine\EventListener;

use App\Entity\Task;
use App\Manager\TaskManagerInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Class TaskSubscriber
 * @package App\Doctrine\EventListener
 */
class TaskSubscriber implements EventSubscriber
{
    private $taskManager;

    /**
     * TaskSubscriber constructor.
     * @param TaskManagerInterface $taskManager
     */
    public function __construct(TaskManagerInterface $taskManager)
    {
        $this->taskManager = $taskManager;
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
        ];
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof Task) {
            return;
        }

        $task = $entity;

        $this->taskManager->setOwner($task);
    }
}