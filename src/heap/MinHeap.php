<?php

namespace olcaytaner\DataStructure\heap;

class MinHeap extends Heap
{
    function __construct(int $N, HeapComparator $comparator)
    {
        parent::__construct($N, $comparator);
    }

    function compare(object $obj1, object $obj2): int
    {
        return -$this->comparator->compare($obj1, $obj2);
    }
}