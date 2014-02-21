<?php

namespace Bwc\FiriBundle\Component\Iterator;

use BWC\FiriBundle\Component\Exception\Exception;

/**
 * IteratorTrait trait
 *
 * @author Igor Pantović <php.igor@gmail.com>
 */
trait IteratorTrait
{
    /**
     * @var callback
     */
    protected $descendCallback;

    /**
     * @var callback
     */
    protected $ascendCallback;

    /**
     * {@inheritDoc}
     */
    public function setDescendCallback($descendCallback)
    {
        $this->descendCallback = $descendCallback;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setAscendCallback($ascendCallback)
    {
        $this->ascendCallback = $ascendCallback;

        return $this;
    }

    /**
     * Calls callback if it exists
     *
     * @param $callback
     *
     * @throws \BWC\FiriBundle\Component\Exception\Exception
     */
    private function callIfExists($callback)
    {
        if ($callback) {
            if ($callback instanceof \Closure) {
                $callback();
            } else if (is_array($callback)) {
                $callback[0]->$callback[1]();
            } else {
                throw new Exception('Invalid callback supplied');
            }
        }
    }
}