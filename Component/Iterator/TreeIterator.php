<?php

namespace Bwc\FiriBundle\Component\Iterator;

/**
 * TreeIterator class
 *
 * @author Igor Pantović <php.igor@gmail.com>
 */
class TreeIterator implements IIterator
{
    use IteratorTrait;

    /**
     * @var \Bwc\FiriBundle\Component\Iterator\BranchIterator;
     */
    private $branch;

    /**
     * @var \Bwc\FiriBundle\Component\Iterator\BushIterator;
     */
    private $bush;

    /**
     * @var \Bwc\FiriBundle\Component\Iterator\IIterator
     */
    private $currentIterator;

    /**
     * Constructs tree iterator
     *
     * @param BranchIterator $branch
     * @param BushIterator $bush
     */
    public function __construct(
        BranchIterator $branch,
        BushIterator $bush
    ) {
        $this->branch = $branch;
        $this->bush   = $bush;

        $this->currentIterator = $branch;
    }

    /**
     * {@inheritDoc}
     */
    public function setDescendCallback($callback)
    {
        $this->descendCallback = $callback;

        $this->branch->setDescendCallback($callback);
        $this->bush->setDescendCallback($callback);
    }

    /**
     * {@inheritDoc}
     */
    public function setAscendCallback($callback)
    {
        $this->ascendCallback = $callback;

        $this->branch->setAscendCallback($callback);
        $this->bush->setAscendCallback($callback);
    }

    /**
     * Moves to next position
     */
    public function next()
    {
        $this->currentIterator->next();

        if (
            $this->currentIterator == $this->branch
            && !$this->currentIterator->valid()
        ) {
            $this->currentIterator = $this->bush;

            $callback = $this->descendCallback;
            if ($callback) {
                if ($callback instanceof \Closure) {
                    $callback();
                } else if (is_array($callback)) {
                    $callback[0]->$callback[1]();
                }
            }
        }
    }

    /**
     * Rewinds
     */
    public function rewind()
    {
        $this->branch->rewind();
        $this->bush->rewind();
    }

    /**
     * Checks if current position is valid
     *
     * @return bool
     */
    public function valid()
    {
        if (
            $this->currentIterator == $this->bush
            && !$this->bush->valid()
        ) {
            return false;
        }

        return true;
    }

    /**
     * Gets current item
     *
     * @return \Bwc\FiriBundle\Component\IItem
     */
    public function current()
    {
        return $this->currentIterator->current();
    }

    /**
     * Gets current key
     *
     * @return int
     */
    public function key()
    {
        return 0;
    }
}