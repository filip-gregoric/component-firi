<?php

namespace BWC\Component\FiriBundle\Component\Iterator;

use BWC\Component\FiriBundle\Component\IItem;

/**
 * ReverseBranchIterator class
 *
 * @author Igor Pantović <php.igor@gmail.com>
 */
class ReverseBranchIterator implements IIterator
{
    use IteratorTrait;

    /**
     * @var IItem
     */
    private $start;

    /**
     * @var IItem
     */
    private $current;

    /**
     * @var int
     */
    private $steps = 0;

    /**
     * @param IItem $item
     */
    public function __construct(IItem $item)
    {
        $this->start   = $item;
        $this->current = $item;
    }

    /**
     * Gets current item
     *
     * @return IItem
     */
    public function current()
    {
        if (0 !== $this->steps) {
            $this->callIfExists($this->descendCallback);
        }

        return $this->current;
    }

    /**
     * Moves to next position
     */
    public function next()
    {
        $this->current = $this->current->getParent();
        $this->steps++;
    }

    /**
     * Rewinds
     */
    public function rewind()
    {
        $this->current = $this->start;
        $this->steps   = 0;
    }

    /**
     * Checks if current position is valid
     *
     * @return bool
     */
    public function valid()
    {
        return $this->current->getParent() !== null;
    }

    /**
     * Gets current key
     *
     * @return int
     */
    public function key()
    {
        return $this->steps;
    }
}