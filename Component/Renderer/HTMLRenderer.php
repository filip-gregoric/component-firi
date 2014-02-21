<?php

namespace BWC\FiriBundle\Component\Renderer;

/**
 * HTMLRenderer class
 *
 * @author Igor Pantović <php.igor@gmail.com>
 */
class HTMLRenderer extends AbstractRenderer
{
    /**
     * @var \DOMElement
     */
    protected $currentNode;

    /**
     * @var \DOMElement
     */
    protected $lastNode;

    /**
     * {@inheritDoc}
     */
    public function onStepDown()
    {
        $menu = new \DOMElement('ul');

        $this->lastNode->appendChild($menu);

        $this->currentNode = $menu;
    }

    /**
     * {@inheritDoc}
     */
    public function onStepUp()
    {
        $this->currentNode = $this->currentNode->parentNode->parentNode;
    }

    /**
     * {@inheritDoc}
     */
    public function render()
    {
        $document = new \DOMDocument();

        $rootMenu = new \DOMElement('ul');
        $document->appendChild($rootMenu);
        $this->currentNode = $rootMenu;
        $this->lastNode    = $rootMenu;

        foreach ($this->iterator as $item) {
            $node = new \DOMElement('li');
            $this->currentNode->appendChild($node);

            $anchor = new \DOMElement('a');
            $node->appendChild($anchor);

            $label = new \DOMText($item->getName());
            $anchor->appendChild($label);

            $this->lastNode = $node;
        }

        return $document->saveHTML();
    }
}