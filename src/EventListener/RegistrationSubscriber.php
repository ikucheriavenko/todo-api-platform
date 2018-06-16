<?php
/**
 * Created by PhpStorm.
 * User: Ivan Kucheriavenko
 * Date: 13.06.18
 * Time: 23:28.
 */

declare(strict_types=1);

namespace App\EventListener;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Dto\User\AuthUser;
use App\Exception\DuplicateRegistrationException;
use App\Manager\AppUserManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class RegistrationSubscriber.
 */
class RegistrationSubscriber implements EventSubscriberInterface
{
    /**
     * @var AppUserManagerInterface
     */
    private $userManager;

    public function __construct(AppUserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @return iterable
     */
    public static function getSubscribedEvents(): iterable
    {
        return [
            KernelEvents::VIEW => [
                ['registerUser', EventPriorities::POST_VALIDATE],
            ],
        ];
    }

    /**
     * @param GetResponseForControllerResultEvent $resultEvent
     *
     * @throws DuplicateRegistrationException
     */
    public function registerUser(GetResponseForControllerResultEvent $resultEvent): void
    {
        $request = $resultEvent->getRequest();

        if ('api_register_users_post_collection' !== $request->attributes->get('_route')) {
            return;
        }

        $user = $resultEvent->getControllerResult();
        if (!$user instanceof AuthUser) {
            return;
        }

        $registered = $this->userManager->findUserByUsername($username = $user->getUsername());
        if ($registered) {
            throw new DuplicateRegistrationException(
                sprintf('The user with username "%s" already exists', $username)
            );
        }

        $this->userManager->registerUser($user);

        $resultEvent->setResponse(new JsonResponse(null, Response::HTTP_CREATED));
    }
}
