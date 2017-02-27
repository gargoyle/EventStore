<?php

namespace Pmc\EventStore\Collection;

use Iterator;
use Pmc\EventStore\StorableEventInterface;

/**
 * @author Gargoyle <g@rgoyle.com>
 */
class StorableEventList implements Iterator
{
    private $eventList;
    private $index;
    
    public function __construct()
    {
        $this->eventList = [];
        $this->index = 0;
    }
    
    public function addEvents(StorableEventList $events)
    {
        foreach ($events as $event) {
            $this->addEvent($event);
        }
    }
    
    public function addEvent(StorableEventInterface $event)
    {
        $this->eventList[] = $event;
    }

    public function current(): StorableEventInterface
    {
        return $this->eventList[$this->index];
    }

    public function key(): \scalar
    {
        return $this->index;
    }

    public function next(): void
    {
        $this->index++;
    }

    public function rewind(): void
    {
        $this->index = 0;
    }

    public function valid(): bool
    {
        return isset($this->eventList[$this->index]);
    }

}
