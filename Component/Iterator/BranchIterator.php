<?php

namespace BWC\Component\FiriBundle\Component\Iterator;

use BWC\Component\FiriBundle\Component\IItem;

/**
 * BranchIterator class
 *
 * @author Igor Pantović <php.igor@gmail.com>
 */
class BranchIterator implements IIterator
{
    use IteratorTrait;

    /**
     * @param IItem[]
     */
    private $itemPath = array();

    /**
     * @var int
     */
    private $position = 0;

    /**
     * @param IItem $item Start item
     */
    public function __construct(IItem $item)
    {
        $current = $item->getParent();

        if ($current) { // If root isn't the only item in structure
            // Until we get to the root item
            while ($current->getParent()) {
                $this->itemPath[] = $current;
                $current          = $current->getParent();
            }
        }

        $this->itemPath = array_reverse($this->itemPath);
    }

    /**
     * Moves to next position
     */
    public function next()
    {
        $this->position++;
    }

    /**
     * Moves to previous position
     */
    public function previous()
    {
        $this->position--;
    }

    /**
     * Rewinds
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * Gets current item
     *
     * @return IItem
     */
    public function current()
    {
        // Prevents descending from __root__ to trigger the callback
        if ($this->position !== 0) {
            $this->callIfExists($this->descendCallback);
        }

        return $this->itemPath[$this->position];
    }

    /**
     * Checks if current position is valid
     *
     * @return bool
     */
    public function valid()
    {
        return isset($this->itemPath[$this->position]);
    }

    /**
     * Gets current key
     *
     * @return int
     */
    public function key()
    {
        return $this->position;
    }
}