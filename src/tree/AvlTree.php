<?php

namespace olcaytaner\DataStructure\tree;

use Closure;
use olcaytaner\DataStructure\Stack;

/**
 * <p>AVL (Adelson-Velskii and Landis) tree is a balanced binary search tree structure. The balance property is very simple
 * and ensures the depth of the tree is in the level of O(log N).</p>
 *
 * <p>In AVL tree, the heights of the left and right subtrees of each node can differ by at most 1. If the heights of the
 * left and right subtrees of a single node differ by more than 1, then that binary search tree is not an AVL tree.</p>
 * @@template T Type of the data stored in the tree node.
 */
class AvlTree extends Tree
{
    public function __construct(Closure $comparator)
    {
        parent::__construct($comparator);
    }

    public function height(?AvlTreeNode $d): int
    {
        if ($d == null) {
            return 0;
        } else {
            return $d->getHeight();
        }
    }

    /**
     * In rotate left, we move node k1 one level up, since due to the binary search tree
     * property k2 > k1, we move node k2 one level down. The links updated are:
     * <ul>
     *     <li>Since k2 > B > k1, the left child of node k2 is now the old right child of k1</li>
     *     <li>The right child of k1 is now k2 </li>
     * </ul>
     * Note that, the root node of the subtree is now k1. In order to modify the parent link of k2, the new root of the
     * subtree is returned by the function.
     * @param AvlTreeNode $k2 Root of the subtree, which does not satisfy the AVL tree property.
     * @return AvlTreeNode new root of the subtree
     */
    private function rotateLeft(AvlTreeNode $k2): AvlTreeNode
    {
        $k1 = $k2->getLeft();
        $k2->setLeft($k1->getRight());
        $k1->setRight($k2);
        $k2->setHeight(max($this->height($k2->getLeft()), $this->height($k2->getRight())) + 1);
        $k1->setHeight(max($this->height($k1->getLeft()), $k1->getRight()->getHeight()) + 1);
        return $k1;
    }

    /**
     * In order to restore the AVL tree property, we move node k2 one level up, since due to the binary search tree
     * property k2 > k1, we move node k1 one level down. The links updated are:
     * <ul>
     *     <li>Since k2 > B > k1, the right child of node k1 is now the old left child of k2.</li>
     *     <li>The left child of k2 is now k1</li>
     * </ul>
     * Note that, the root node of the subtree is now k2. In order to modify the parent link of k1, the new root of the
     * subtree is returned by the function.
     * @param AvlTreeNode $k1 Root of the subtree, which does not satisfy the AVL tree property.
     * @return AvlTreeNode The new root of the subtree
     */
    private function rotateRight(AvlTreeNode $k1): AvlTreeNode
    {
        $k2 = $k1->getRight();
        $k1->setRight($k2->getLeft());
        $k2->setLeft($k1);
        $k2->setHeight(max($k2->getLeft()->getHeight(), $this->height($k2->getRight())) + 1);
        $k1->setHeight(max($this->height($k1->getLeft()), $this->height($k1->getRight())) + 1);
        return $k2;
    }

    /**
     * <p>In the first phase we will do single right rotation on the subtree rooted with k1. With this rotation, the left
     * child of node k2 will be k1, whereas the right child of node k1 will be B (the old left child of node k2).</p>
     *
     * <p>In the second phase, we will do single left rotation on the subtree rooted with k3. With this rotation, the
     * right child of node k2 will be k3, whereas the left child of node k3 will be C (the old right child of k2).</p>
     *
     * Note that, the new root node of the subtree is now k2. In order to modify the parent link of k3, the new root of
     * the subtree is returned by the function.
     * @param AvlTreeNode $k3 Root of the subtree, which does not satisfy the AVL tree property.
     * @return AvlTreeNode The new root of the subtree
     */
    private function doubleRotateLeft(AvlTreeNode $k3): AvlTreeNode{
        $k3->setLeft($this->rotateRight($k3->getLeft()));
        return $this->rotateLeft($k3);
    }

    /**
     * <p>In the first phase we will do single right rotation on the subtree rooted with k3. With this rotation, the right
     * child of node k2 will be k3, whereas the left child of node k3 will be C (the old right child of node k2).</p>
     *
     * <p>In the second phase, we will do single right rotation on the subtree rooted with k1. With this rotation, the left
     * child of node k2 will be k1, whereas the left child of node k1 will be B (the old left child of k2).</p>
     *
     * Note that, the new root node of the subtree is now k2. In order to modify the parent link of k1, the new root of
     * the subtree is returned by the function.
     * @param AvlTreeNode $k1 Root of the subtree, which does not satisfy the AVL tree property.
     * @return AvlTreeNode The new root of the subtree
     */
    private function doubleRotateRight(AvlTreeNode $k1): AvlTreeNode{
        $k1->setRight($this->rotateLeft($k1->getRight()));
        return $this->rotateRight($k1);
    }

    /**
     * <p>First we will proceed with the stages the same when we add a new node into a binary search tree. For this, we
     * start from the root node and traverse in down manner. The current node we visit is represented with x and the
     * previous node is represented with y. At each time we compare the value of the current node with the value of the
     * new node. If the value of the new node is smaller than the value of the current node, the new node will be
     * inserted into the left subtree of the current node. For this, we will continue with the left child to process. If
     * the reverse is true, that is, if the value of the new node is larger than the value of the current node, the new
     * node will be inserted into the right subtree of the current node. In this case, we will continue with the right
     * child to process. At each step, we store the current node in a separate stack.</p>
     *
     * <p>When we insert a new node into an AVL tree, we need to change the heights of the nodes and check if the AVL tree
     * property is satisfied or not. Only the height of the nodes, which we visit while we are finding the place for the
     * new node, can be changed. So, what we should do is to pop those nodes from the stack one by one and change the
     * heights of those nodes.</p>
     *
     * <p>Similarly, the nodes, which we visit while we are finding the place for the new node, may no longer satisfy the
     * AVL tree property. So what we should do is to pop those nodes from the stack one by one and calculate the
     * difference of the heights of their left and right subtrees. If the height difference is 2, the AVL tree property
     * is not satisfied. If we added the new node into the left subtree of the left child, we need to do left single
     * rotation, if we added into the right subtree of the left child, we need to do left double rotation, if we added
     * into the left subtree of the right child, we need to do right double rotation, if we added into the right subtree
     * of the right child, we need to the right single rotation. Since  the root node of the subtree will be changed
     * after a rotation, the new child of y will be the new root node t.</p>
     * @param AvlTreeNode|TreeNode $node Node to be inserted.
     */
    public function insert(AvlTreeNode|TreeNode $node): void
    {
        $LEFT = 1;
        $RIGHT = 2;
        $y = null;
        $x = $this->root;
        $dir1 = 0;
        $dir2 = 0;
        $c = new Stack();
        while ($x != null){
            $y = $x;
            $c->push($y);
            $dir1 = $dir2;
            $comparator = $this->comparator;
            if ($comparator($node->getData(), $x->getData()) < 0){
                $x = $x->getLeft();
                $dir2 = $LEFT;
            } else {
                $x = $x->getRight();
                $dir2 = $RIGHT;
            }
        }
        $this->insertChild($y, $node);
        while (!$c->isEmpty()){
            $x = $c->pop();
            if ($x != null){
                $x->setHeight(max($this->height($x->getLeft())), $this->height($x->getRight())) + 1;
                if (abs($this->height($x->getLeft()) - $this->height($x->getRight())) == 2){
                    if ($dir1 == $LEFT){
                        if ($dir2 == $LEFT){
                            $t = $this->rotateLeft($x);
                        } else {
                            $t = $this->doubleRotateLeft($x);
                        }
                    } else {
                        if ($dir2 == $LEFT){
                            $t = $this->doubleRotateRight($x);
                        } else {
                            $t = $this->rotateRight($x);
                        }
                    }
                    $y = $c->pop();
                    $this->insertChild($y, $t);
                    break;
                }
            }
        }
    }

    public function insertData(mixed $data): void
    {
        $this->insert(new AvlTreeNode($data));
    }
}