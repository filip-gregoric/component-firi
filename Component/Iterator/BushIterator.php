<?php

namespace BWC\Component\FiriBundle\Component\Iterator;

use BWC\Component\FiriBundle\Component\IItem;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * BushIterator class
 *
 * @author Igor Pantović <php.igor@gmail.com>
 */
class BushIterator extends \RecursiveIteratorIterator implements IIterator
{
    use IteratorTrait;

    protected $filter;

    protected $accessor;

    private $iterated;

    /**
     * @param IItem $root
     * @param int $maxDepth
     * @param array $filter
     */
    public function __construct(IItem $root, $maxDepth = -1, $filter = array())
    {
        parent::__construct($root, parent::SELF_FIRST);
        parent::setMaxDepth($maxDepth);
        $this->filter   = $filter;
        $this->accessor = PropertyAccess::getPropertyAccessor();
        $this->iterated = new \SplObjectStorage();
    }

    /**
     * Called by \RecursiveIteratorIterator when descending
     */
    public function beginChildren()
    {
        parent::beginChildren();

        $this->call($this->descendCallback);
    }

    /**
     * Called by \RecursiveIteratorIterator when ascending
     */
    public function endChildren()
    {
        parent::endChildren();

        $this->call($this->ascendCallback);
    }

    /**
     * Moves to next item in structure.
     * Respects filter.
     */
    public function next()
    {
        do {
            $this->iterated->attach($this->current());
            parent::next();
        } while ($this->valid() && (!$this->passes($this->current()) || $this->iterated->contains($this->current())));
    }

    /**
     * @param array $filter
     */
    public function setFilter($filter)
    {
        $this->filter = $filter;
    }

    /**
     * @return array
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * Checks whether item passes filter requirements
     */
    protected function passes(IItem $item)
    {
        foreach ($this->filter as $propertyPath => $value) {
            $realValue = $this->accessor->getValue($item, $propertyPath);

            if ($realValue != $value) {
                return false;
            }
        }

        return true;
    }

    /**
     * Calls a callback if it exists and is callable
     *
     * @param $callback Callback
     */
    private function call($callback)
    {
        if ($this->getInnerIterator()->hasChildren()) {
            $this->callIfExists($callback);
        }
    }
}