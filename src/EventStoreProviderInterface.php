<?php

namespace Pmc\EventStore;

use Pmc\ {
    EventSourceLib\AggregateId,
    EventStore\Collection\StorableEventList
};

/**
 *
 * @author Gargoyle <g@rgoyle.com>
 */
interface EventStoreProviderInterface
{
    public function storeEvents(StorableEventList $eventList): void;
    public function storeEvent(StorableEventInterface $event): void;
    
    public function getEventsForAggregate(AggregateId $aggregateId): StorableEventList;
}
