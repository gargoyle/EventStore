<?php

namespace Pmc\EventStore\Collection;

/**
 * @author Gargoyle <g@rgoyle.com>
 */
class StorableEventList implements \Iterator
{
    private $eventList;
    private $index;
    
    public function __construct()
    {
        $this->eventList = [];
        $this->index = 0;
    }
    
    public function addEvent(\Pmc\EventStore\StorableEventInterface $event)
    {
        $this->eventList[] = $event;
    }

    public function current(): \Pmc\EventStore\StorableEventInterface
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
