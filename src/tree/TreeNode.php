<?php

namespace olcaytaner\DataStructure\tree;

class TreeNode
{
    protected mixed $data;
    protected ?TreeNode $left;
    protected ?TreeNode $right;

    public function __construct($data)
    {
        $this->data = $data;
        $this->left = null;
        $this->right = null;
    }

    /**
     * @return mixed
     */
    public function getData(): mixed
    {
        return $this->data;
    }

    public function getLeft(): ?TreeNode
    {
        return $this->left;
    }

    public function getRight(): ?TreeNode
    {
        return $this->right;
    }

    public function setLeft(?TreeNode $left): void
    {
        $this->left = $left;
    }

    public function setRight(?TreeNode $right): void
    {
        $this->right = $right;
    }
}