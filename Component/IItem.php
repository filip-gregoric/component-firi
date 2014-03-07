<?php

namespace BWC\Component\FiriBundle\Component;

/**
 * IItem interface
 *
 * @author Igor Pantović <php.igor@gmail.com>
 */
interface IItem extends \RecursiveIterator, NestedItemInterface
{
    /**
     * Adds child.
     *
     * @param IItem $item
     *
     * @return IItem
     */
    public function addChild(IItem $item);

    /**
     * Removes child.
     *
     * @param IItem $item
     *
     * @return IItem
     */
    public function removeChild(IItem $item);

    /**
     * Checks whether child exists.
     *
     * @param IItem $item
     *
     * @return boolean
     */
    public function hasChild(IItem $item);

    /**
     * Sets parent.
     *
     * @param IItem $item
     *
     * @return IItem
     */
    public function setParent(IItem $item);

    /**
     * Creates isolated clone of Item.
     *
     * Isolated clone has exactly the same properties
     * as original but it's parent and children relations
     * are cleared.
     *
     * @return IItem
     */
    public function isolatedClone();

    /**
     * RecursiveIterator dictates really sensible method names...
     * This method does what getChildren() should do
     *
     * @return array|\ArrayAccess
     */
    public function getAllChildren();

    /**
     * @param $dataObject
     */
    public function setData($dataObject);

    /**
     * Compares this item to another.
     *
     * @param $anotherItem
     * @return bool
     */
    public function equals($anotherItem);
}