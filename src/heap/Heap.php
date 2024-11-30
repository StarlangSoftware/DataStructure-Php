<?php

namespace olcaytaner\DataStructure\heap;

/**
 * <p>The heap data structure is a binary tree structure consisting of N elements. It shows the basic properties of the
 * binary tree data structure. The heap has a root node and each node of it has at most two child nodes
 * (left and right). The root node of the tree is shown at the top, and its branches grow not to up but to down manner.
 * </p>
 *
 * <p>In a heap, if the maximum element is in the root node and all nodes are smaller than its descendants, then this heap
 * is called max-heap, if the minimum element is in the root node and all nodes are larger than its descendants, then
 * this heap is called min-heap.</p>
 * @template T Type of the data stored in the heap node.
 */
class Heap
{
    private array $array;
    private int $count;
    private int $n;
    protected HeapComparator $comparator;

    /**
     * Constructor of the heap. According to the comparator, the heap could be min or max heap.
     * @param int $N Maximum number of elements in the heap.
     * @param HeapComparator $comparator Comparator function to compare two elements.
     */
    function __construct(int $N, HeapComparator $comparator)
    {
        $this->n = $N;
        $this->comparator = $comparator;
        $this->array = [];
    }

    /**
     * Checks if the heap is empty or not.
     * @return True if the heap is empty, false otherwise.
     */
    public function isEmpty(): bool{
        return $this->count === 0;
    }

    /**
     * Swaps two heap nodes in the heap given their indexes.
     * @param int $index1 Index of the first node to swap.
     * @param int $index2 Index of the second node to swap.
     */
    private function swapNode(int $index1, int $index2)
    {
        $tmp = $this->array[$index1];
        $this->array[$index1] = $this->array[$index2];
        $this->array[$index2] = $tmp;
    }

    protected function compare(object $obj1, object $obj2): int
    {
        return 0;
    }

    /**
     * The function percolates down from a node of the tree to restore the max-heap property. Left or right children are
     * checked, if one of them is larger than the current element of the heap, the iteration continues. The iteration is,
     * determining the largest one of the node's children and switching that node's value with the current node's value.
     * We need to check if current node's left and right children exist or not. These checks are done with for the left
     * child and with for the right child.
     * @param int $no Index of the starting node to restore the max-heap property.
     */
    protected function percolateDown(int $no): void
    {
        $left = 2 * $no + 1;
        $right = 2 * $no + 2;
        while (($left < $this->count && $this->compare($this->array[$no].getData(), $this->array[$left].getData()) < 0) ||
                ($right < $this->count && $this->compare($this->array[$no].getData(), $this->array[$right].getData()) < 0)) {
            if ($right >= $this->count || $this->compare($this->array[$left].getData(), $this->array[$right].getData()) > 0) {
                $this->swapNode($no, $left);
                $no = $left;
            } else {
                $this->swapNode($no, $right);
                $no = $right;
            }
            $left = 2 * $no + 1;
            $right = 2 * $no + 2;
        }
    }

    /**
     * The function percolates up from a node of the tree to restore the max-heap property. As long as the max-heap
     * property is corrupted, the parent and its child switch their values. We need to pay attention that, the
     * calculated index of the parent must be a valid number. In other words, while going upper levels,we need to see
     * that we can not go up if we are at the root of the tree.
     * @param int $no Index of the starting node to restore the max-heap property.
     */
    protected function percolateUp(int $no): void
    {
        $parent = floor(($no - 1) / 2);
        while ($parent >= 0 && $this->compare($this->array[$parent].getData(), $this->array[$no].getData()) < 0){
            $this->swapNode($parent, $no);
            $no = $parent;
            $parent = floor(($no - 1) / 2);
        }
    }

    /**
     * The function will return the first element, therefore the first element is stored in the variable, and the first
     * element of the heap is set to the last element of the heap. After that, in order to restore the max-heap
     * property, we percolate down the tree using the function. As a last step, the number of element in the heap is
     * decremented by one.
     * @return object The first element
     */
    public function delete(): object
    {
        $tmp = $this->array[0];
        $this->array[0] = $this->array[$this->count - 1];
        $this->percolateDown(0);
        $this->count = $this->count - 1;
        return $tmp.getData();
    }

    /**
     * The insertion of a new element to the heap, proceeds from the leaf nodes to the root node. First the new element
     * is added to the end of the heap, then as long as the max-heap property is corrupted, the new element switches
     * place with its parent.
     * @param object $data New element to be inserted.
     */
    public function insert(object $data): void
    {
        if ($this->count < $this->n) {
            $this->count = $this->count + 1;
        }
        $this->array[$this->count - 1] = new HeapNode($data);
        $this->percolateUp($this->count - 1);
    }
}