<?php

namespace BWC\FiriBundle\Component\Renderer;

/**
 * MarkdownRenderer class
 *
 * @author Igor Pantović <php.igor@gmail.com>
 */
class MarkdownRenderer extends AbstractRenderer
{

    /**
     * @var string
     */
    protected $rendered = '';

    /**
     * @var string
     */
    protected $spacing = '';

    /**
     * {@inheritDoc}
     */
    public function onStepDown()
    {
        $this->spacing .= "    ";
    }

    /**
     * {@inheritDoc}
     */
    public function onStepUp()
    {
        $this->spacing = substr($this->spacing, 0, -4);
    }

    /**
     * {@inheritDoc}
     */
    public function render()
    {
        $this->rendered = '';
        $this->spacing  = '';

        foreach ($this->iterator as $item) {
            $this->rendered .= "\n" . $this->spacing . '* ' . $item->getName();
        }

        return $this->rendered;
    }
}