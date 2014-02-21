<?php

namespace Bwc\FiriBundle\Component\Iterator;

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
     * @return \Bwc\FiriBundle\Component\Iterator\IIterator
     */
    public function setDescendCallback($callback);

    /**
     * @param $callback
     *
     * @return \Bwc\FiriBundle\Component\Iterator\IIterator
     */
    public function setAscendCallback($callback);
}