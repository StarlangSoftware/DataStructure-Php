<?php

namespace olcaytaner\DataStructure\tree;

use Closure;

/**
 * <p>In the computer science literature, the structures such as AVL tree, splay tree, red-black tree are proposed, which
 * show the search tree property and also remain balanced after insertion and deletion operations.</p>
 *
 * <p>Another possibility of constructing a balanced tree structure is to store not only a single value but more than one
 * value in a node. These type of tree structures are generalizations of the binary trees and called d-ary tree
 * structures in the computer science literature. 2-3-4 trees, B-tree, B+ trees can be given as example d-ary tree
 * structures. B+ trees, is one of the d-ary tree structures and used often in database systems.</p>
 *
 * <p>B+ tree is a dynamic search tree structure and consists of two parts, an index part and a data part. The index part
 * is of d-ary tree structure, each node stores d {@literal <} m {@literal <} 2d values. d is a parameter of B+ tree, shows the capacity of B+
 * tree and called as the degree of the tree. The root node is the single exception to this rule and can store
 * 1 {@literal <} m {@literal <} 2d values. Each node also contains m + 1 links to point to its m + 1 child nodes. With the help of these
 * links, the tree can be traversed in top-down manner. Let Pi represent the link pointing to the node i and Ki
 * represent the i'th value in the same node, the i'th child and the ascendants of this child can take values between
 * the interval Ki {@literal <} K {@literal <} Ki+1. The data are stored in the leaf nodes and due to the definition of a tree, the leaf nodes
 * can not have children.</p>
 * @template T Type of the data stored in the B tree node.
 */
class BTree
{
    private ?BTreeNode $root = null;
    private Closure $comparator;
    private int $d;

    /**
     * Constructor of the tree. According to the comparator, the tree could contain any object.
     * @param int $d Parameter d in d-ary tree.
     * @param Closure $comparator Comparator function to compare two elements.
     */
    public function __construct(int $d, Closure $comparator)
    {
        $this->d = $d;
        $this->comparator = $comparator;
    }

    /**
     * We start searching from the root node, the node with which we compare the searched value at each stage is
     * represented by b, and we continue the search until we arrive the leaf nodes. In order to understand the subtree
     * of node b where our searched value resides, we need to compare the searched value with the values Ki. For this,
     * the function named position is given. If the searched value is larger than the last value of node b, we need to
     * continue the search with the rightmost child. If the searched value is smaller than the i. value of node b, we
     * need to continue the search with the i. child. As a last step, the function returns the leaf node of node b.
     * @param mixed $value Value searched in B+ tree.
     * @return ?BTreeNode If the value exists in the tree, the function returns the node that contains the node. Otherwise, it
     * returns null.
     */
    public function search(mixed $value): ?BTreeNode
    {
        $b = $this->root;
        while ($b != null && !$b->isLeaf()) {
            $child = $b->position($value, $this->comparator);
            if ($child < $b->getM() && $b->getK($child) == $value) {
                return $b;
            }
            $b = $b->getChild($child);
        }
        if ($b != null) {
            $child = $b->position($value, $this->comparator);
            if ($child < $b->getM() && $b->getK($child) == $value) {
                return $b;
            }
        }
        return null;
    }

    public function insertData(mixed $data): void
    {
        if ($this->root == null){
            $this->root = new BTreeNode($this->d);
        }
        if ($this->root->isLeaf()){
            $s = $this->root->insertLeaf($data, $this->comparator);
            if ($s != null){
                $tmp = $this->root;
                $this->root = new BTreeNode($this->d, $tmp, $s, $tmp->getK($this->d));
            }
        } else {
            $s = $this->root->insertNode($data, $this->comparator, true);
            if ($s != null){
                $this->root = $s;
            }
        }
    }
}