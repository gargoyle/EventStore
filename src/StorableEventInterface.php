<?php

namespace Pmc\EventStore;

use Pmc\EventSourceLib\ {
    AggregateId,
    EventClassName,
    EventId
};

/**
 * @author Gargoyle <g@rgoyle.com>
 */
interface StorableEventInterface
{
    public function eventId(): EventId;
    public function aggregateId(): AggregateId;
    public function timestamp(): float;
    public function eventClassName(): EventClassName;
    public function toArray(): array;
    public static function fromArray(array $data);
}
