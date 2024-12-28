<?php

use olcaytaner\DataStructure\tree\AvlTree;
use olcaytaner\DataStructure\tree\BTree;
use olcaytaner\DataStructure\tree\Tree;
use PHPUnit\Framework\TestCase;

class TreeTest extends TestCase
{
    public function testTree(){
        $compare = function(int $item1, int $item2): int {
            return $item1 - $item2;
        };
        $tree = new Tree($compare);
        $tree->insertData(4);
        $tree->insertData(6);
        $tree->insertData(2);
        $tree->insertData(5);
        $tree->insertData(3);
        $tree->insertData(1);
        $tree->insertData(7);
        $this->assertTrue($tree->search(3) != null);
        $this->assertTrue($tree->search(8) == null);
    }

    public function testTree2(){
        $compare = function(int $item1, int $item2): int {
            return $item1 - $item2;
        };
        $tree = new AvlTree($compare);
        for ($i = 1; $i <= 31; $i++){
            $tree->insertData($i);
        }
        for ($i = 1; $i < 32; $i++){
            $this->assertTrue($tree->search($i) != null);
        }
        $this->assertTrue($tree->search(32) == null);
    }

    public function testTree3(){
        $compare = function(int $item1, int $item2): int {
            return $item1 - $item2;
        };
        $tree = new BTree(1, $compare);
        for ($i = 1; $i <= 31; $i++){
            $tree->insertData($i);
        }
        for ($i = 1; $i < 32; $i++){
            $this->assertTrue($tree->search($i) != null);
        }
        $this->assertTrue($tree->search(32) == null);
    }

}
