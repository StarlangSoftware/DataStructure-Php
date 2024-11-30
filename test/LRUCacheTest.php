<?php

namespace olcaytaner\test;

use olcaytaner\DataStructure\LRUCache;
use PHPUnit\Framework\TestCase;

class LRUCacheTest extends TestCase
{
    public function test1(){
        $cache = new LRUCache(50000);
        $cache->add("item1", "1");
        $cache->add("item2", "2");
        $cache->add("item3", "3");
        $this->assertTrue($cache->contains("item2"));
        $this->assertTrue(!$cache->contains("item4"));
    }

    public function test2(){
        $cache = new LRUCache(50000);
        $cache->add("item1", "1");
        $cache->add("item2", "2");
        $cache->add("item3", "3");
        $this->assertTrue($cache->contains("item2"));
        $this->assertTrue($cache->contains("item1"));
    }

    public function test3(){
        $cache = new LRUCache(50000);
        for ($i = 0; $i < 10000; $i++){
            $cache->add($i, $i);
        }
        for ($i = 0; $i < 1000; $i++){
            $this->assertTrue($cache->contains(rand(0, 9999)));
        }
    }

}
