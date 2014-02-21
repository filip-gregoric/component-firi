<?php

namespace BWC\Component\FiriBundle\Component\Iterator;

/**
 * IIterator interface
 *
 * @author Igor Pantović <php.igor@gmail.com>
 */
interface IIterator extends \Iterator
{
    /**
     * @param $callback
     *
     * @return IIterator
     */
    public function setDescendCallback($callback);

    /**
     * @param $callback
     *
     * @return IIterator
     */
    public function setAscendCallback($callback);
}