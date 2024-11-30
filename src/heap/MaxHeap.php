<?php

namespace olcaytaner\DataStructure\heap;

use Closure;

class MaxHeap extends Heap
{
    function __construct(int $N, Closure $comparator)
    {
        parent::__construct($N, $comparator);
    }

    function compare(mixed $obj1, mixed $obj2): int
    {
        $comparator = $this->comparator;
        return $comparator($obj1, $obj2);
    }
}