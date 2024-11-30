<?php

namespace olcaytaner\DataStructure\tree;

use Closure;

/**
 * Tree structure is also a non-linear data structure. Different from the tree structure we see in the nature, the
 * tree data structure has its root on top and develops its branches down.
 * @template T Type of the data stored in the tree node.
 */
class Tree
{
    protected ?TreeNode $root = null;
    protected Closure $comparator;

    /**
     * Constructor of the tree. According to the comparator, the tree could contain any object.
     * @param Closure $comparator Comparator function to compare two elements.
     */
    public function __construct(Closure $comparator)
    {
        $this->comparator = $comparator;
    }

    /**
     * The search starts from the root node, and we represent the node, with which we compare the searched value, with
     * d. If the searched value is equal to the value of the current node, we have found the node we search for, the
     * function will return that node. If the searched value is smaller than the value of the current node , the number
     * we search for must be on the left subtree of the current node, therefore the new current node must be the left
     * child of the current node. As the last case, if the searched value is larger than the value of the current node,
     * the number we search for must be on the right subtree of the current node, therefore the new current node must be
     * the right child of the current node. If this search continues until the leaf nodes of the tree, and we can't find
     * the node we search for, node d will be null and the function will return null.
     * @param object $value Searched value
     * @return ?TreeNode If the value exists in the tree, the function returns the node that contains the node. Otherwise, it
     * returns null.
     */
    public function search(object $value): ?TreeNode
    {
        $d = $this->root;
        while ($d != null) {
            $comparator = $this->comparator;
            if ($comparator($d->getData(), $value) == 0) {
                return $d;
            } else {
                if ($comparator($d->getData(), $value) > 0) {
                    $d = $d->getLeft();
                } else {
                    $d = $d->getRight();
                }
            }
        }
        return null;
    }

    /**
     * Inserts a child to its parent as left or right child.
     * @param TreeNode|null $parent New parent of the child node.
     * @param TreeNode $child Child node.
     */
    protected function insertChild(?TreeNode $parent, TreeNode $child): void
    {
        if ($parent == null) {
            $this->root = $child;
        } else {
            $comparator = $this->comparator;
            if ($comparator($child->getData(), $parent->getData()) < 0) {
                $parent->setLeft($child);
            } else {
                $parent->setRight($child);
            }
        }
    }

    /**
     * In order to add a new node into a binary search tree, we need to first find out the place where we will insert
     * the new node. For this, we start from the root node and traverse the tree down. At each step, we compare the
     * value of the new node with the value of the current node. If the value of the new node is smaller than the value
     * of the current node, the new node will be inserted into the left subtree of the current node. To accomplish this,
     * we continue the process with the left child of the current node. If the situation is reverse, that is, if the
     * value of the new node is larger than the value of the current node, the new node will be inserted into the right
     * subtree of the current node. In this case, we continue the process with the right child of the current node.
     * @param TreeNode $node Node to be inserted.
     */
    public function insert(TreeNode $node): void
    {
        $y = null;
        $x = $this->root;
        while ($x != null) {
            $y = $x;
            $comparator = $this->comparator;
            if ($comparator($node->getData(), $x->getData()) < 0) {
                $x = $x->getLeft();
            } else {
                $x = $x->getRight();
            }
        }
        $this->insertChild($y, $node);
    }

    public function insertData(mixed $data): void
    {
        $this->insert(new TreeNode($data));
    }
}