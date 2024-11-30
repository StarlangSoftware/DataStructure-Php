<?php

namespace olcaytaner\DataStructure\heap;

class HeapNode
{
    private object $data;

    /**
     * Constructor of HeapNode.
     * @param object $data Data to be stored in the heap node.
     */
    public function __construct(object $data)
    {
        $this->data = $data;
    }

    /**
     * Mutator of the data field
     * @return object Data
     */
    public function getData(): object
    {
        return $this->data;
    }
}