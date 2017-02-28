<?php

namespace Pmc\EventStore\Driver\MySQL;

use mysqli_stmt;
use Pmc\ {
    EventSourceLib\AggregateId,
    EventSourceLib\Event\AggregateEvent,
    EventSourceLib\Event\AggregateEventList,
    EventStore\EventStoreProviderInterface,
    EventStore\Exception\DatabaseCommandFailure,
    ObjectLib\Serializable\ASH
};

/**
 * @author Gargoyle <g@rgoyle.com>
 */
class Driver implements EventStoreProviderInterface
{

    private $dbConn;

    public function __construct(string $host, string $dbname, string $username, string $password)
    {
        $connectionHandler = new ConnectionHandler($host,
                $dbname,
                $username,
                $password);
        $this->dbConn = $connectionHandler->getConnection();
    }

    private function createFetchForAggregateStatement(AggregateId $aggregateId): mysqli_stmt
    {
        $inAggregateId = (string) $aggregateId;
        $stmt = $this->dbConn->prepare("SELECT eventClassName, eventData FROM events WHERE aggregateId = ?");
        $stmt->bind_param("s", $inAggregateId);
        $stmt->execute();
        return $stmt;
    }

    public function getEventsForAggregate(AggregateId $aggregateId): AggregateEventList
    {
        $eventList = new AggregateEventList();
        $stmt = $this->createFetchForAggregateStatement($aggregateId);

        $outEventClassName = null;
        $outEventData = null;
        $stmt->bind_result($outEventClassName,
                $outEventData);
        while ($stmt->fetch()) {
            $event = ASH::unserialize(['className' => $outEventClassName, 'data' => $outEventData]);
            $eventList->addEvent($event);
        }

        $stmt->close();
        return $eventList;
    }

    private function decodeEventData(string $dataAsJson): array
    {
        return json_decode($dataAsJson, true);
    }
    
    private function encodeEventData(array $data): string
    {
        return json_encode($data,
                JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION);
    }

    private function createInsertEventStatement(&$inEventId, &$inAggregateId, &$inTimestamp, &$inEventClassName, &$inEventData)
    {
        $stmt = $this->dbConn->prepare('INSERT INTO events ('
                . 'eventId, '
                . 'aggregateId, '
                . 'timestamp, '
                . 'eventClassName, '
                . 'eventData) VALUES (?,?,?,?,?)');
        $stmt->bind_param("ssdss",
                $inEventId,
                $inAggregateId,
                $inTimestamp,
                $inEventClassName,
                $inEventData);
        return $stmt;
    }

    public function storeEvent(AggregateEvent $event): void
    {
        $inAggregateId = null;
        $inEventId = null;
        $inTimestamp = null;
        $inEventClassName = null;
        $inEventData = null;

        $stmt = $this->createInsertEventStatement($inEventId,
                $inAggregateId,
                $inTimestamp,
                $inEventClassName,
                $inEventData);
        $inAggregateId = (string) $event->aggregateId();
        $inEventId = (string) $event->eventId();
        $inTimestamp = (string) $event->timestamp();
        $inEventClassName = (string) get_class($event);
        $inEventData = $this->encodeEventData($event->toArray());
        if (!$stmt->execute()) {
            throw new DatabaseCommandFailure(sprintf(
                    "Failed to store event! (%s: %s)",
                    $stmt->errno, $stmt->error));
        }

        $stmt->close();
    }

    public function storeEvents(AggregateEventList $eventList): void
    {
        $inAggregateId = null;
        $inEventId = null;
        $inTimestamp = null;
        $inEventClassName = null;
        $inEventData = null;

        $stmt = $this->createInsertEventStatement($inEventId,
                $inAggregateId,
                $inTimestamp,
                $inEventClassName,
                $inEventData);
        foreach ($eventList as $event) {
            $inAggregateId = (string) $event->aggregateId();
            $inEventId = (string) $event->eventId();
            $inTimestamp = (string) $event->timestamp();
            $inEventClassName = (string) get_class($event);
            $inEventData = $this->encodeEventData($event->toArray());
            $stmt->execute();
        }

        $stmt->close();
    }
}
