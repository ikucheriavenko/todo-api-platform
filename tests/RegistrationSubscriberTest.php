<?php

/**
 * Created by PhpStorm.
 * User: Ivan Kucheriavenko
 * Date: 13.06.18
 * Time: 23:32
 */

declare(strict_types=1);

namespace App\Tests\EventListener;

use App\EventListener\RegistrationSubscriber;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class RegistrationSubscriberTest extends TestCase
{
    private $onParsedSubscriber;
    private $crudAPI;

    protected function setUp()
    {
//        $this->crudAPI = $this->createMock(CRUDApi::class);
//        $this->onParsedSubscriber = new OnParsedSubscriber($this->crudAPI);
    }

    public function testGetSubscribedEvents()
    {
        foreach (RegistrationSubscriber::getSubscribedEvents() as $subscribedEvent) {
            $this->assertInternalType('callable', [$this->onParsedSubscriber, current($subscribedEvent)]);
        }
    }

    /**
     * @dataProvider provideParsedEntryContext
     */
    public function testRegisterUser($productId, $url, $uniqueId, $dataSet)
    {
        $parsedEntry = (new ParsedEntry())
            ->setProductId($productId)
            ->setUrl($url)
            ->setUniqueId($uniqueId)
            ->setResultSet($dataSet);

        $parsedEntries = new ArrayCollection([$parsedEntry]);

        $event = $this->createMock(OnParsedEvent::class);
        $event
            ->expects($this->any())
            ->method('getParsedEntries')
            ->will($this->returnValue($parsedEntries))
        ;

        $this->crudAPI
            ->expects($this->once())
            ->method('upsertAutos')
            ->with($this->equalTo([$parsedEntry->toArray()]));


        $this->onParsedSubscriber->publishResults($event);
    }

    public function provideParsedEntryContext()
    {
        return array(
            array(1, 'http://entryUrl.com', 'unique_id', json_encode(['data_key' => 'data_value'])),
        );
    }
}