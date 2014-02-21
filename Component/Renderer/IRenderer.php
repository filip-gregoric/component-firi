<?php

namespace BWC\FiriBundle\Component\Renderer;

use BWC\FiriBundle\Component\Iterator\IIterator;

/**
 * IRenderer interface
 *
 * @author Igor Pantović <php.igor@gmail.com>
 */
interface IRenderer
{
    /**
     * Sets iterator used when rendering
     *
     * @param \Bwc\FiriBundle\Component\Iterator\IIterator $iterator
     *
     * @return \Bwc\FiriBundle\Component\Iterator\IIterator
     */
    public function setIterator(IIterator $iterator);

    /**
     * Renders structure
     *
     * @return string
     */
    public function render();
}