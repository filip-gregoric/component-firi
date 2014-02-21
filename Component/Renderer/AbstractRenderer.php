<?php

namespace BWC\FiriBundle\Component\Renderer;

use BWC\FiriBundle\Component\Iterator\IIterator;

/**
 * AbstractRenderer class
 *
 * @author Igor Pantović <php.igor@gmail.com>
 */
abstract class AbstractRenderer implements IRenderer
{

    /**
     * @var \Bwc\FiriBundle\Component\IItem[]
     */
    protected $iterator;

    /**
     * {@inheritDoc}
     */
    public function setIterator(IIterator $iterator)
    {
        $this->iterator = $iterator;

        $iterator->setDescendCallback(array($this, 'onStepDown'));
        $iterator->setAscendCallback(array($this, 'onStepUp'));

        return $this;
    }

    /**
     * Called when iterator descends
     */
    public function onStepDown()
    {
    }

    /**
     * Called when iterator ascends
     */
    public function onStepUp()
    {
    }

    /**
     * {@inheritDoc}
     */
    abstract public function render();
}