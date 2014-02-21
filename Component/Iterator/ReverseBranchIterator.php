<?php

namespace Bwc\FiriBundle\Component\Iterator;

use BWC\FiriBundle\Component\IItem;

/**
 * ReverseBranchIterator class
 *
 * @author Igor Pantović <php.igor@gmail.com>
 */
class ReverseBranchIterator implements IIterator
{
    use IteratorTrait;

    /**
     * @var \Bwc\FiriBundle\Component\IItem
     */
    private $start;

    /**
     * @var \Bwc\FiriBundle\Component\IItem
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
     * @return \Bwc\FiriBundle\Component\IItem
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
        return $this->current->getName() !== '__root__';
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