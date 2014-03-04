<?php

namespace BWC\Component\FiriBundle\Component;

use BWC\Component\FiriBundle\Component\Exception\Exception;

/**
 * Item class
 *
 * @author Igor Pantović <php.igor@gmail.com>
 */
abstract class Item implements IItem
{
    /**
     * @var IItem[]
     */
    protected $children = array();

    /**
     * @var IItem
     */
    protected $parent;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $attributes = array();

    /**
     * @var int
     */
    private $position = 0;

    /**
     * {@inheritDoc}
     */
    public function addChild(IItem $item)
    {
        if ($item === $this) {
            throw new Exception('Item can\'t be a child to itself');
        }
        $this->children[] = $item;

        $item->setParent($this);

        if ($this implements OrderedItemInterface) {
            usort($this->children, function ($a, $b) {
                if ($a->getOrder() == $b->getOrder()) {
                    return 0;
                }

                return $a->getOrder() > $b->getOrder() ? -1 : 1;
            });
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function removeChild(IItem $item)
    {
        unset($this->children[array_search($item, $this->children, true)]);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function hasChild(IItem $item)
    {
        return in_array($item, $this->children);
    }

    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * {@inheritDoc}
     *
     * Shouldn't be called manually.
     */
    public function setParent(IItem $item)
    {
        if (!$item->hasChild($this)) {
            throw new Exception(
                'Please do not call this function manually. Parent
                assignation is managed automatically.'
            );
        }
        if ($item === $this) {
            throw new Exception('Item can\'t be a child to itself');
        }

        $this->parent = $item;

        return $this;
    }

    /**
     * Gets children on current position
     *
     * @return \RecursiveIterator
     */
    public function getChildren()
    {
        return $this->children[$this->position];
    }

    /**
     * Checks if current position is valid
     *
     * @return bool
     */
    public function valid()
    {
        return isset($this->children[$this->position]);
    }

    /**
     * Checks whether item has children
     *
     * @return bool
     */
    public function hasChildren()
    {
        return count($this->children) > 0;
    }

    /**
     * Moves to next position
     */
    public function next()
    {
        $this->position++;
    }

    /**
     * Gets current item
     *
     * @return IItem
     */
    public function current()
    {
        return $this->children[$this->position];
    }

    /**
     * Rewinds
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * Gets current key
     *
     * @return int
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * Handles "getters" for attributes
     *
     * @param $name
     * @param $arguments
     *
     * @return string|null
     */
    public function __call($name, $arguments)
    {
        if (array_key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        } else {
            return null;
        }
    }

    /**
     * Sets attribute
     *
     * @param string $name
     * @param mixed $value
     *
     * @return $this
     */
    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;

        return $this;
    }

    public function __isset($name)
    {
        return isset($this->attributes[$name]);
    }

    public function __get($name)
    {
        return $this->attributes[$name];
    }

    /**
     * {@inheritDoc}
     */
    public function isolatedClone()
    {
        $clone = clone $this;

        $clone->children = array();
        $clone->parent   = null;

        return $clone;
    }

    /**
     * {@inheritDoc}
     */
    public function getAllChildren()
    {
        return $this->children;
    }
}