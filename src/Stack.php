<?php

namespace olcaytaner\DataStructure;

/**
 * Stack is a list data structure consisting of many elements. There are two types of operations defined for the
 * elements of the stack: Adding an element to the stack (push) and removing an element from the stack (pop). In a
 * stack, to be popped element is always the last pushed element. Also, when an element is pushed on to the stack, it
 * is placed on top of the stack (at the end of the list).
 */
class Stack
{
    private array $list;

    public function __construct()
    {
        $this->list = [];
    }

    /**
     * When we push an element on top of the stack, we only need to increase the field top by 1 and place the new
     * element on this new position. If the stack is full before this push operation, we can not push.
     * @param object $item Item to insert.
     */
    public function push(object $item): void
    {
        $this->list[] = $item;
    }

    /**
     * When we remove an element from the stack (the function also returns that removed element), we need to be careful
     * if the stack was empty or not. If the stack is not empty, the topmost element of the stack is returned and the
     * field top is decreased by 1. If the stack is empty, the function will return null.
     * @return object|null $object The removed element
     */
    public function pop(): ?object{
        if (count($this->list) == 0) {
            return null;
        }
        return array_pop($this->list);
    }

    /**
     * The function checks whether an array-implemented stack is empty or not. The function returns true if the stack is
     * empty, false otherwise.
     * @return True if the stack is empty, false otherwise.
     */
    public function isEmpty(): bool
    {
        return empty($this->list);
    }
}