<?php

namespace olcaytaner\DataStructure\tree;

use Closure;

class BTreeNode
{
    private array $K;
    private int $m;
    private int $d;
    private bool $leaf;
    private array $children;

    /**
     * Another constructor of a B+ Tree node. By default, it is not a leaf node. Adds two children.
     * @param int $d d in d-ary tree.
     * @param BTreeNode|null $firstChild First child of the root node.
     * @param BTreeNode|null $secondChild Second child of the root node.
     * @param null $newK First value in the node.
     */
    public function __construct(int $d, BTreeNode $firstChild = null, BTreeNode $secondChild = null, $newK = null)
    {
        $this->d = $d;
        $this->K = [];
        $this->children = [];
        if ($firstChild == null) {
            $this->m = 0;
            $this->leaf = true;
        } else {
            $this->m = 1;
            $this->leaf = false;
            $this->children[] = $firstChild;
            if ($secondChild != null) {
                $this->children[] = $secondChild;
            }
            if ($newK != null) {
                $this->K[] = $newK;
            }
        }
    }

    /**
     * Searches the position of value in the list K. If the searched value is larger than the last value of node, we
     * need to continue the search with the rightmost child. If the searched value is smaller than the i. value of node,
     * we need to continue the search with the i. child.
     * @param object $value Searched value
     * @param Closure $comparator Comparator function which compares two elements.
     * @return int The position of searched value in array K.
     */
    public function position(object $value, Closure $comparator): int
    {
        if ($this->m == 0) {
            return 0;
        }
        if ($comparator($value, $this->K[$this->m - 1]) > 0) {
            return $this->m;
        } else {
            for ($i = 0; $i < $this->m; $i++) {
                if ($comparator($value, $this->K[$i]) <= 0) {
                    return $i;
                }
            }
        }
        return -1;
    }

    /**
     * Add the new value insertedK to the array K into the calculated position index.
     * @param int $index Place to insert new value
     * @param object $insertedK New value to be inserted.
     */
    private function insertIntoK(int $index, object $insertedK): void
    {
        for ($i = $this->m; $i > $index; $i--) {
            $this->K[$i] = $this->K[$i - 1];
        }
        $this->K[$index] = $insertedK;
    }

    /**
     * Transfers the last d values of the current node to the newNode.
     * @param BTreeNode $newNode New node.
     */
    private function moveHalfOfTheKToNewNode(BTreeNode $newNode): void
    {
        for ($i = 0; $i < $this->d; $i++) {
            $newNode->K[$i] = $this->K[$i + $this->d + 1];
        }
        $newNode->m = $this->d;
    }

    /**
     * Transfers the last d links of the current node to the newNode.
     * @param BTreeNode $newNode New node.
     */
    private function moveHalfOfTheChildrenToNewNode(BTreeNode $newNode): void
    {
        for ($i = 0; $i < $this->d; $i++) {
            $newNode->children[$i] = $this->children[$i + $this->d + 1];
        }
    }

    /**
     * Transfers the last d values and the last d links of the current node to the newNode.
     * @param BTreeNode $newNode node.
     */
    private function moveHalfOfTheElementsToNewNode(BTreeNode $newNode): void
    {
        $this->moveHalfOfTheKToNewNode($newNode);
        $this->moveHalfOfTheChildrenToNewNode($newNode);
    }

    /**
     * First the function position is used to determine the node or the subtree to which the new node will be added.
     * If this subtree is a leaf node, we call the function insertLeaf that will add the value to a leaf node. If this
     * subtree is not a leaf node the function calls itself with the determined subtree. Both insertNode and insertLeaf
     * functions, if adding a new value/node to that node/subtree necessitates a new child node to be added to the
     * parent node, they will both return the new added node and the node obtained by dividing the original node. If
     * there is not such a restructuring, these functions will return null. If we add a new child node to the parent
     * node, first we open a space for that child node in the value array K, then we add this new node to the array K.
     * After adding there are two possibilities:
     * <ul>
     *     <li>After inserting the new child node, the current node did not exceed its capacity. In this case, we open
     *     space for the link, which points to the new node, in the array children and place that link inside of this
     *     array.</li>
     *     <li>After inserting the new child node, the current node exceed its capacity. In this case, we need to create
     *     newNode, transfer the last d values and the last d links of the current node to the newNode. As a last case,
     *     if the divided node is the root node, we need to create a new root node and the first child of this new root
     *     node will be b, and the second child of the new root node will be newNode.</li>
     * </ul>
     * @param object $value Value to be inserted into B+ tree.
     * @param Closure $comparator Comparator function to compare two elements.
     * @param bool $isRoot If true, value is inserted as a root node, otherwise false.
     * @return ?BTreeNode If inserted node results in a creation of a node, the function returns that node, otherwise null.
     */
    public function insertNode(object $value, Closure $comparator, bool $isRoot): ?BTreeNode
    {
        $child = $this->position($value, $comparator);
        if (!$this->children[$child]->leaf) {
            $s = $this->children[$child]->insertNode($value, $comparator, false);
        } else {
            $s = $this->children[$child]->insertLeaf($value, $comparator);
        }
        if ($s == null) {
            return null;
        }
        $this->insertIntoK($child, $this->children[$child]->K[$this->d]);
        if ($this->m < 2 * $this->d) {
            $this->children[$child + 1] = $s;
            $this->m++;
            return null;
        } else {
            $newNode = new BTreeNode($this->d);
            $newNode->leaf = false;
            $this->moveHalfOfTheElementsToNewNode($newNode);
            $newNode->children[$this->d] = $s;
            $this->m = $this->d;
            if ($isRoot) {
                return new BTreeNode($this->d, $this, $newNode, $this->K[$this->d]);
            } else {
                return $newNode;
            }
        }
    }

    /**
     * First the function position is used to determine the position where the new value will be placed Then we open a
     * space for that value in the value array K, then we add this new value to the array K into the calculated
     * position. At this stage there are again two possibilities:
     * <ul>
     *     <li>After inserting the new value, the current leaf node did not exceed its capacity. The function returns
     *     null.</li>
     *     <li>After inserting the new value, the current leaf node exceed its capacity. In this case, we need to create
     *     the newNode, and transfer the last d values of node b to this newNode.</li>
     * </ul>
     * @param object $value Value to be inserted into B+ tree.
     * @param Closure $comparator Comparator function to compare two elements.
     * @return ?BTreeNode If inserted node results in a creation of a node, the function returns that node, otherwise null.
     */
    public function insertLeaf($value, Closure $comparator): ?BTreeNode
    {
        $child = $this->position($value, $comparator);
        $this->insertIntoK($child, $value);
        if ($this->m < 2 * $this->d) {
            $this->m++;
            return null;
        } else {
            $newNode = new BTreeNode($this->d);
            $this->moveHalfOfTheKToNewNode($newNode);
            $this->m = $this->d;
            return $newNode;
        }
    }

    public function isLeaf(): bool
    {
        return $this->leaf;
    }

    public function getM(): int
    {
        return $this->m;
    }

    public function getK(int $index): mixed
    {
        return $this->K[$index];
    }

    public function getChild(int $index): array
    {
        return $this->children[$index];
    }
}