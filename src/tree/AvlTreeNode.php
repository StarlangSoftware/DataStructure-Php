<?php

namespace olcaytaner\DataStructure\tree;

class AvlTreeNode extends TreeNode
{
    protected int $height;

    public function __construct($data)
    {
        parent::__construct($data);
        $this->height = 1;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function setHeight(int $height): void
    {
        $this->height = $height;
    }
}