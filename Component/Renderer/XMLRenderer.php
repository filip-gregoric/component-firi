<?php

namespace BWC\Component\FiriBundle\Component\Renderer;

/**
 * XMLRenderer class
 *
 * @author Igor Pantović <php.igor@gmail.com>
 */
class XMLRenderer extends AbstractRenderer
{

    const NODE_NAME       = 'item';
    const LABEL_NODE_NAME = 'label';

    /**
     * @var \SimpleXMLElement
     */
    protected $currentNode;

    /**
     * @var \SimpleXMLElement
     */
    protected $lastNode;

    /**
     * {@inheritDoc}
     */
    public function onStepUp()
    {
        // Seriously? Learn XPath !
        $this->currentNode = $this->lastNode
            ->xpath("parent::*")[0]
            ->xpath("parent::*")[0];
    }

    /**
     * {@inheritDoc}
     */
    public function onStepDown()
    {
        $this->currentNode = $this->lastNode->addChild('children');
    }

    /**
     * {@inheritDoc}
     */
    public function render()
    {
        $xml = new \SimpleXMLElement('<menu/>');

        $this->lastNode    = $xml;
        $this->currentNode = $xml;

        foreach ($this->iterator as $item) {
            $node = $this->currentNode->addChild(self::NODE_NAME);
            $node->addChild(self::LABEL_NODE_NAME, $item->getName());

            $this->lastNode = $node;
        }

        return $xml->asXML();
    }
}