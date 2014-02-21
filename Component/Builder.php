<?php

namespace Bwc\FiriBundle\Component;

/**
 * Builder class.
 * Utility for building FIRI item structure programmatically.
 *
 * @author Igor Pantović <php.igor@gmail.com>
 */
class Builder
{
    const ITEM_CLASS = '\BWC\Component\FIRI\Item';

    /**
     * @var IItem
     */
    private $root;

    /**
     * @var IItem
     */
    private $currentItem;

    /**
     * @var IItem
     */
    private $lastItem;

    /**
     * @var string
     */
    private $itemClass = self::ITEM_CLASS;

    /**
     * Constructs builder and creates root item
     */
    public function __construct()
    {
        $this->root        = new $this->itemClass('__root__');
        $this->currentItem = $this->root;
    }

    /**
     * Sets item class
     *
     * @param $itemClass string
     *
     * @return $this
     */
    public function setItemClass($itemClass)
    {
        $this->itemClass = $itemClass;

        return $this;
    }

    /**
     * Gets item class
     *
     * @return string
     */
    public function getItemClass()
    {
        return $this->itemClass;
    }

    /**
     * Adds new item to current one
     *
     * @param $item string|\BWC\Component\FIRI\IItem Item instance or item class name
     * @param null $storeVar If an uninitialized variable is supplied, created
     *                       item will be assigned to it
     *
     * @return $this
     *
     * @throws \Exception
     */
    public function addItem($item, &$storeVar = null)
    {
        if (is_string($item)) {
            $item = new $this->itemClass($item);

            if (!$storeVar) {
                $storeVar = $item;
            }
        }

        if (!$item instanceof IItem) {
            throw new \Exception(
                'Instance of \BWC\Component\FIRI\IItem or item class name
                must be passed'
            );
        }

        $this->currentItem->addChild($item);
        $this->lastItem = $item;

        return $this;
    }

    /**
     * Sets last inserted item as current
     *
     * @return $this
     */
    public function begin()
    {
        $this->currentItem = $this->lastItem;

        return $this;
    }

    /**
     * Sets last inserted item's parent as current
     *
     * @return $this
     */
    public function end()
    {
        $this->currentItem = $this->currentItem->getParent();

        return $this;
    }

    /**
     * If a variable is supplied it will assign it to builder's root item
     *
     * @param null $storeVal
     *
     * @return $this
     */
    public function __invoke(&$storeVal = null)
    {
        if (!$storeVal) {
            $storeVal = $this->root;
        }

        return $this;
    }
}