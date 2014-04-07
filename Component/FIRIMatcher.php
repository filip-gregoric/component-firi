<?php

namespace BWC\Component\FiriBundle\Component;

use BWC\Component\FiriBundle\Component\Exception\Exception;
use BWC\Component\FiriBundle\Component\Filter\IFilter;
use BWC\Component\FiriBundle\Component\Iterator\BushIterator;

/**
 * FIRIMatcher class.
 * Finds items in structure.
 *
 * @author Igor Pantović <php.igor@gmail.com>
 */
class FIRIMatcher
{

    /**
     * @var IFilter
     */
    protected $filters = [];

    /**
     * Registers filter within matcher.
     *
     * @param IFilter $filter
     */
    public function addFilter(IFilter $filter)
    {
        $this->filters[$filter->getName()] = $filter;
    }

    /**
     * Returns first item matching given filter. If none matches returns null.
     *
     * @param IFilter|string $filter Filter name or IFilter instance
     * @param IItem $rootItem
     * @param array $options
     *
     * @return IItem|null
     */
    public function match($filter, IItem $rootItem, array $options = array())
    {
        foreach (new BushIterator($rootItem) as $item) {
            /** @var $item IItem */
            if ($this->selectFilter($filter)->filter($item, $options)) {
                return $item;
            }
        }

        return null;
    }

    /**
     * Returns all items matching given filter.
     *
     * @param $filter
     * @param IItem $rootItem
     * @param array $options
     *
     * @return IItem[]
     */
    public function matchAll($filter, IItem $rootItem, array $options = array())
    {
        $matched = [];

        foreach (new BushIterator($rootItem) as $item) {
            if ($this->selectFilter($filter)->filter($item, $options)) {
                $matched[] = $item;
            }
        }

        return $matched;
    }

    /**
     * Selects filter instance.
     *
     * @param $filter
     *
     * @return IFilter
     *
     * @throws Exception
     */
    protected function selectFilter($filter)
    {
        // If instance of IFilter is passed, just use it
        if ($filter instanceof IFilter) {
            return $filter;
        }

        // If string passed, find filter with that name or throw an exception
        // if it isn't registered
        else if (is_string($filter)) {
            if (isset($this->filters[$filter])) {
                return $this->filters[$filter];
            } else {
                throw new Exception(sprintf(
                    'No filter with name "%s" registered',
                    $filter
                ));
            }
        } else { // Unrecognized type
            throw new Exception(sprintf(
                'Filter must be instance of IFilter or string representing
                filter name. %s given.',
                gettype($filter)
            ));
        }
    }
}
