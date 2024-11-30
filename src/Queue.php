<?php

namespace olcaytaner\DataStructure;

class Queue
{
    private array $list;
    private int $head;
    private int $tail;
    private int $maxSize;

    /**
     * Constructor of the queue data structure
     * @param int $maxSize Maximum size of the queue
     */
    public function __construct(int $maxSize)
    {
        $this->maxSize = $maxSize;
        $this->list = [];
        $this->head = 0;
        $this->tail = 0;
    }

    /**
     * Adds a set of elements to the end of the queue
     * @param array $items Elements to be inserted into the queue
     */
    public function enqueueAll(array $items): void
    {
        foreach ($items as $item) {
            $this->enqueue($item);
        }
    }

    /**
     * Adds an element to the end of the queue.
     * @param object $item Element added to the queue.
     */
    public function enqueue(object $item): void{
        $this->list[$this->tail] = $item;
        $this->tail = ($this->tail + 1) % $this->maxSize;
    }

    /**
     * Removes an element from the start of the queue.
     * @return object Removed item
     */
    public function dequeue(): object
    {
        $item = $this->list[$this->head];
        $this->head = ($this->head + 1) % $this->maxSize;
        return $item;
    }

    /**
     * Returns head of the queue.
     * @return object Head of the queue
     */
    public function peek(): object{
        return $this->list[$this->head];
    }

    /**
     * Checks if the queue is empty or not.
     * @return bool True if the queue is empty, false otherwise.
     */
    public function isEmpty(): bool
    {
        return $this->head === $this->tail;
    }
}