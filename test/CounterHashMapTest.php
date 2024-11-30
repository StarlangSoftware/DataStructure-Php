<?php

namespace olcaytaner\DataStructure;

use PHPUnit\Framework\TestCase;

class CounterHashMapTest extends TestCase
{
    public function testPut1(){
        $counterHashMap = new CounterHashMap();
        $counterHashMap->put("item1");
        $counterHashMap->put("item2");
        $counterHashMap->put("item3");
        $counterHashMap->put("item1");
        $counterHashMap->put("item2");
        $counterHashMap->put("item1");
        $this->assertEquals(3, $counterHashMap->count("item1"));
        $this->assertEquals(2, $counterHashMap->count("item2"));
        $this->assertEquals(1, $counterHashMap->count("item3"));
    }

    public function testPut2(){
        $counterHashMap = new CounterHashMap();
        for ($i = 0; $i < 1000; $i++){
            $counterHashMap->put(rand(0, 999));
        }
        $count = 0;
        for ($i = 0; $i < 1000; $i++){
            $count += $counterHashMap->count($i);
        }
        $this->assertEquals(1000, $count);
    }

    public function testSumOfCounts(){
        $counterHashMap = new CounterHashMap();
        for ($i = 0; $i < 1000; $i++){
            $counterHashMap->put(rand(0, 999));
        }
        $this->assertEquals(1000, $counterHashMap->sumOfCounts());
    }

    public function testPut3(){
        $counterHashMap = new CounterHashMap();
        for ($i = 0; $i < 10000; $i++){
            $counterHashMap->put(10000 * (mt_rand() / mt_getrandmax()));
        }
        $this->assertEqualsWithDelta(0.632, $counterHashMap->size() / 10000.0, 0.01);
    }

    public function testPutNTimes1(){
        $counterHashMap = new CounterHashMap();
        $counterHashMap->putNTimes("item1", 2);
        $counterHashMap->putNTimes("item2", 3);
        $counterHashMap->putNTimes("item3", 6);
        $counterHashMap->putNTimes("item1", 2);
        $counterHashMap->putNTimes("item2", 3);
        $counterHashMap->putNTimes("item1", 2);
        $this->assertEquals(6, $counterHashMap->count("item1"));
        $this->assertEquals(6, $counterHashMap->count("item2"));
        $this->assertEquals(6, $counterHashMap->count("item3"));
    }

    public function testPutNTimes2(){
        $counterHashMap = new CounterHashMap();
        for ($i = 0; $i < 1000; $i++){
            $counterHashMap->putNTimes(rand(0, 999), $i + 1);
        }
        $this->assertEquals(500500, $counterHashMap->sumOfCounts());
    }

    public function testMax(){
        $counterHashMap = new CounterHashMap();
        $counterHashMap->put("item1");
        $counterHashMap->put("item2");
        $counterHashMap->put("item3");
        $counterHashMap->put("item1");
        $counterHashMap->put("item2");
        $counterHashMap->put("item1");
        $this->assertEquals("item1", $counterHashMap->max());
    }

    public function testAdd1(){
        $counterHashMap1 = new CounterHashMap();
        $counterHashMap1->put("item1");
        $counterHashMap1->put("item2");
        $counterHashMap1->put("item3");
        $counterHashMap1->put("item1");
        $counterHashMap1->put("item2");
        $counterHashMap1->put("item1");
        $counterHashMap2 = new CounterHashMap();
        $counterHashMap2->putNTimes("item1", 2);
        $counterHashMap2->putNTimes("item2", 3);
        $counterHashMap2->putNTimes("item3", 6);
        $counterHashMap2->putNTimes("item1", 2);
        $counterHashMap2->putNTimes("item2", 3);
        $counterHashMap2->putNTimes("item1", 2);
        $counterHashMap1->add($counterHashMap2);
        $this->assertEquals(9, $counterHashMap1->count("item1"));
        $this->assertEquals(8, $counterHashMap1->count("item2"));
        $this->assertEquals(7, $counterHashMap1->count("item3"));
    }

    public function testTopN1(){
        $counterHashMap = new CounterHashMap();
        $counterHashMap->put("item1");
        $counterHashMap->put("item2");
        $counterHashMap->put("item3");
        $counterHashMap->put("item1");
        $counterHashMap->put("item2");
        $counterHashMap->put("item1");
        $this->assertEquals("item1", $counterHashMap->topN(1)[0][0]);
        $this->assertEquals("item2", $counterHashMap->topN(2)[1][0]);
        $this->assertEquals("item3", $counterHashMap->topN(3)[2][0]);
    }
}
