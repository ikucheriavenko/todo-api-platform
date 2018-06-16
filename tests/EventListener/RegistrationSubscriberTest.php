<?php
/**
 * Created by PhpStorm.
 * User: Ivan Kucheriavenko
 * Date: 13.06.18
 * Time: 23:32
 */

declare(strict_types=1);

namespace App\Tests\EventListener;

use App\Dto\User\AuthUser;
use App\Entity\Task;
use App\Entity\User;
use App\EventListener\RegistrationSubscriber;
use App\Exception\DuplicateRegistrationException;
use App\Manager\AppUserManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;

class RegistrationSubscriberTest extends TestCase
{
    /** @var  RegistrationSubscriber */
    private $registrationSubscriber;
    /** @var  AppUserManagerInterface */
    private $userManager;
    /** @var  GetResponseForControllerResultEvent */
    private $viewEvent;
    /** @var  Request */
    private $request;

    protected function setUp()
    {
        $this->request = new Request();

        $this->viewEvent = $this->createMock(GetResponseForControllerResultEvent::class);
        $this->viewEvent
            ->expects($this->any())
            ->method('getRequest')
            ->will($this->returnValue($this->request));

        $this->userManager = $this->createMock(AppUserManagerInterface::class);
        $this->registrationSubscriber = new RegistrationSubscriber($this->userManager);
    }

    public function testGetSubscribedEvents()
    {
        foreach (RegistrationSubscriber::getSubscribedEvents() as $name => $eventCalls) {
            foreach ($eventCalls as $call) {
                $this->assertInternalType('callable', [$this->registrationSubscriber, current($call)]);
            }
        }
    }

    public function testRegisterUserShouldHandleOnlyRegistration()
    {
        $this->setUpFakeRoute();

        $this->userManager
            ->expects($this->never())
            ->method($this->anything());

        $this->registrationSubscriber->registerUser($this->viewEvent);
    }

    public function testRegisterUserShouldProcessOnlyAuthUser()
    {
        $this->setUpRegistrationRoute();

        $this->eventReturnsNotAuthUser();

        $this->userManager
            ->expects($this->never())
            ->method($this->anything());

        $this->registrationSubscriber->registerUser($this->viewEvent);
    }

    public function testRegisterUserShouldAvoidDuplication()
    {
        $this->expectException(DuplicateRegistrationException::class);

        $this->setUpRegistrationRoute();

        $username = 'test';
        $this->eventReturnsAuthUser($username);
        $this->userManager
            ->expects($this->once())
            ->method('findUserByUsername')
            ->willReturn((new User())->setUsername($username));

        $this->registrationSubscriber->registerUser($this->viewEvent);
    }

    public function testRegisterUser()
    {
        $this->setUpRegistrationRoute();

        $username = 'test';
        $authUser = $this->eventReturnsAuthUser($username);
        $this->userManager
            ->expects($this->once())
            ->method('findUserByUsername')
            ->willReturn(null);

        $this->userManager
            ->expects($this->once())
            ->method('registerUser')
            ->with($authUser);

        $this->viewEvent
            ->expects($this->once())
            ->method('setResponse')
            ->with($this->isInstanceOf(JsonResponse::class));

        $this->registrationSubscriber->registerUser($this->viewEvent);
    }

    protected function setUpFakeRoute()
    {
        $notRegistrationRoute = 'bar';
        $this->request->attributes->set('_route', $notRegistrationRoute);
    }

    protected function setUpRegistrationRoute()
    {
        $registrationRoute = 'api_register_users_post_collection';
        $this->request->attributes->set('_route', $registrationRoute);
    }

    protected function eventReturnsNotAuthUser()
    {
        $this->viewEvent
            ->expects($this->once())
            ->method('getControllerResult')
            ->willReturn($task = new Task());

        return $task;
    }

    protected function eventReturnsAuthUser($username)
    {
        $this->viewEvent
            ->expects($this->once())
            ->method('getControllerResult')
            ->willReturn($authUser = (new AuthUser())->setUsername($username));

        return $authUser;
    }
}