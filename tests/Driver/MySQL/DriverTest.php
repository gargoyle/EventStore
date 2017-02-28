<?php

namespace Pmc\EventStore\Tests\Driver\MySQL;

use mysqli;
use PHPUnit\Framework\TestCase;
use Pmc\ {
    EventSourceLib\Aggregate\AggregateId,
    EventStore\Driver\MySQL\Driver,
    EventStore\Tests\TestEvent
};




/**
 * @author Paul Court <emails@paulcourt.co.uk>
 */
class DriverTest extends TestCase
{

    public static function setUpBeforeClass()
    {
        $mysqli = new mysqli('localhost:13306', 'root', '', 'test_event_store');
        $mysqli->query("TRUNCATE TABLE events");
    }

    private function getDriver()
    {
        $driver = new Driver(
                "localhost:13306",
                "test_event_store",
                "root",
                "");
        return $driver;
    }

    public function testWillStoreEvent()
    {
        $driver = $this->getDriver();
        $event = new TestEvent();
        $driver->storeEvent($event);
    }

    public function testWillFetchEventsForAggregate()
    {
        $driver = $this->getDriver();
        $eventList = $driver->getEventsForAggregate(AggregateId::fromString('9ee99c4a-fc33-11e6-a9e6-80e650033120'));
        $count = 0;
        foreach ($eventList as $event) {
            $count++;
        }
        $this->assertEquals(1, $count);
    }

}
