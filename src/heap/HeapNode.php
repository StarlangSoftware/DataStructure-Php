<?php

namespace olcaytaner\DataStructure\heap;

class HeapNode
{
    private mixed $data;

    /**
     * Constructor of HeapNode.
     * @param object $data Data to be stored in the heap node.
     */
    public function __construct(mixed $data)
    {
        $this->data = $data;
    }

    /**
     * Mutator of the data field
     * @return object Data
     */
    public function getData(): mixed
    {
        return $this->data;
    }
}