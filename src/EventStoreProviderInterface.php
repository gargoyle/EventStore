<?php

namespace Pmc\EventStore;

use Pmc\EventSourceLib\ {
    Aggregate\AggregateId,
    Event\AggregateEvent,
    Event\AggregateEventList
};



/**
 *
 * @author Gargoyle <g@rgoyle.com>
 */
interface EventStoreProviderInterface
{
    public function storeEvents(AggregateEventList $eventList): void;
    public function storeEvent(AggregateEvent $event): void;
    
    public function getEventsForAggregate(AggregateId $aggregateId): AggregateEventList;
}
