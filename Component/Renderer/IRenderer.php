<?php

namespace BWC\Component\FiriBundle\Component\Renderer;

use BWC\Component\FiriBundle\Component\Iterator\IIterator;

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
     * @param IIterator $iterator
     *
     * @return IIterator
     */
    public function setIterator(IIterator $iterator);

    /**
     * Renders structure
     *
     * @return string
     */
    public function render();
}