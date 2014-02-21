<?php

namespace BWC\FiriBundle\Component\Renderer;

/**
 * TxtRenderer class
 *
 * @author Igor Pantović <php.igor@gmail.com>
 */
class TxtRenderer extends AbstractRenderer
{
    /**
     * @var string
     */
    protected $render = '';

    /**
     * @var string
     */
    protected $spacing = '';

    /**
     * {@inheritDoc}
     */
    public function onStepDown()
    {
        $this->render .= "\n" . $this->spacing . "\\";
        $this->spacing .= ' ';
    }

    /**
     * {@inheritDoc}
     */
    public function onStepUp()
    {
        $this->spacing = substr($this->spacing, 0, -1);
        $this->render .= "\n" . $this->spacing . "/";
    }

    /**
     * {@inheritDoc}
     */
    public function render()
    {
        $this->render  = '';
        $this->spacing = '';

        foreach ($this->iterator as $item) {
            $this->render .= "\n" . $this->spacing . '|- ' . $item->getName();
        }

        return $this->render;
    }
}