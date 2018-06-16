<?php
/**
 * Created by PhpStorm.
 * User: Ivan Kucheriavenko
 * Date: 6/16/18
 * Time: 11:34 AM.
 */

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Task;

/**
 * Interface TaskManagerInterface.
 */
interface TaskManagerInterface
{
    /**
     * @param Task $task
     *
     * @return Task
     */
    public function setOwner(Task $task): Task;
}
