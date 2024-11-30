<?php

namespace olcaytaner\DataStructure\heap;

interface HeapComparator
{
    public function compare(object $obj1, object $obj2): int;
}