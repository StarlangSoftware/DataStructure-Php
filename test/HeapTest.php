<?php

namespace olcaytaner\test;

use olcaytaner\DataStructure\heap\MaxHeap;
use olcaytaner\DataStructure\heap\MinHeap;
use PHPUnit\Framework\TestCase;

class HeapTest extends TestCase
{

    public function testMaxHeap(){
        $compare = function(int $item1, int $item2): int {
            return $item1 - $item2;
        };
        $maxHeap = new MaxHeap(8, $compare);
        $maxHeap->insert(4);
        $maxHeap->insert(6);
        $maxHeap->insert(2);
        $maxHeap->insert(5);
        $maxHeap->insert(3);
        $maxHeap->insert(1);
        $maxHeap->insert(7);
        $this->assertEquals(7, $maxHeap->delete());
        $this->assertEquals(6, $maxHeap->delete());
        $this->assertEquals(5, $maxHeap->delete());
    }

    public function testMinHeap(){
        $compare = function(int $item1, int $item2): int {
            return $item1 - $item2;
        };
        $minHeap = new MinHeap(8, $compare);
        $minHeap->insert(4);
        $minHeap->insert(6);
        $minHeap->insert(2);
        $minHeap->insert(5);
        $minHeap->insert(3);
        $minHeap->insert(1);
        $minHeap->insert(7);
        $this->assertEquals(1, $minHeap->delete());
        $this->assertEquals(2, $minHeap->delete());
        $this->assertEquals(3, $minHeap->delete());
    }

}
