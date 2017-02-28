<?php

namespace Pmc\EventStore\Tests;

use Pmc\ {
    EventSourceLib\Aggregate\AggregateId,
    EventSourceLib\Event\AggregateEvent,
    EventSourceLib\Event\EventId,
    ObjectLib\Id,
    Session\Session
};

/**
 * @author Paul Court <emails@paulcourt.co.uk>
 */
class TestEvent implements AggregateEvent
{

    private $dummyData = 'This is a test event';
    private $aggregateId;
    private $eventId;
    private $timestamp;

    public function __construct()
    {
        $this->aggregateId = AggregateId::fromString('9ee99c4a-fc33-11e6-a9e6-80e650033120');
//        $this->eventId = EventId::fromString('8ce4d256-fc34-11e6-935a-80e650033120');
        $this->eventId = new EventId();
        $this->timestamp = 1488121437.1968;
    }

    public function aggregateId(): AggregateId
    {
        return $this->aggregateId;
    }

    public function eventClassName(): string
    {
        return self::class;
    }

    public function eventId(): EventId
    {
        return $this->eventId;
    }

    public function timestamp(): float
    {
        return $this->timestamp;
    }

    public function toArray(): array
    {
        return [
            'aggregateId' => (string) $this->aggregateId(),
            'eventId' => (string) $this->eventId(),
            'eventClassName' => (string) $this->eventClassName(),
            'timestamp' => $this->timestamp(),
            'dummyData' => (string) $this->dummyData
        ];
    }

    public static function fromArray(array $data)
    {
        $instance = new self();
        $instance->aggregateId = AggregateId::fromString($data['aggregateId']);
        $instance->eventId = EventId::fromString($data['eventId']);
        $instance->timestamp = (float)$data['timestamp'];
        $instance->dummyData = (string)$data['dummyData'];
        return $instance;
    }

    public function session(): Session
    {
        return new Session((string)new Id());
    }

}
