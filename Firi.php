<?php

namespace BWC\Component\FiriBundle;

use BWC\Component\FiriBundle\Component\Filter\PropertyFilter;
use BWC\Component\FiriBundle\Component\FIRIMatcher;
use BWC\Component\FiriBundle\Component\Iterator\BushIterator;
use BWC\Component\FiriBundle\Component\Iterator\BranchIterator;
use BWC\Component\FiriBundle\Loader\ILoader;
use BWC\Component\FiriBundle\Component\Exception\Exception;
use BWC\Component\FiriBundle\Component\IItem;
use BWC\Component\FiriBundle\Service\ProxyGenerator;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class Firi
{
    /**
     * @var ILoader
     */
    private $loader;

    /**
     * @var ProxyGenerator
     */
    private $proxyGenerator;

    /**
     * @var string
     */
    private $dataClass;

    /**
     * @var string
     */
    private $itemClass;

    /**
     * @var FIRIMatcher
     */
    private $matcher;

    /**
     * @param string $dataClass
     * @param string $itemClass
     * @param ILoader $loader
     * @param ProxyGenerator $proxyGenerator
     */
    public function __construct($dataClass, $itemClass, ILoader $loader, ProxyGenerator $proxyGenerator)
    {
        $this->dataClass = $dataClass;
        $this->itemClass = $itemClass;
        $this->loader = $loader;
        $this->proxyGenerator = $proxyGenerator;
        $this->matcher = new FIRIMatcher();
    }

    /**
     * @param IItem $item
     * @param int|null $depth
     * @return IItem
     */
    public function getBush(IItem $item = null, $depth = null)
    {
        if (null === $depth) {
            $depth = -1;
        }

        $root = $this->load($item);
        $proxy = $this->proxyGenerator->getProxy(new BushIterator($root, $depth), $root);

        return $proxy;
    }

    /**
     * @param IItem $item
     * @return IItem
     */
    public function getBranch(IItem $item = null)
    {
        $root = $this->load($item);
        $proxy = $this->proxyGenerator->getProxy(new BranchIterator($root), $root);

        return $proxy;
    }

    /**
     * @param IItem $item
     * @param Component\IItem $parent
     * @return IItem
     */
    public function addChild(IItem $item, IItem $parent = null)
    {
        if($parent){
            $parent->addChild($item);
        } else {
            $this->getBush()->addChild($item);
        }
        return $item;
    }

    /**
     * @param IItem $item
     * @return null|IItem
     * @throws Component\Exception\Exception
     */
    public function remove(IItem $item)
    {
        if (null === $item->getParent()) {
            throw new Exception('Root item can\'t be removed');
        }

        $parent = $item->getParent();
        $parent->removeChild($item);

        return $parent;
    }

    /**
     * @param IItem $item
     * @param array $properties
     * @return IItem|null
     */
    public function match(IItem $item, array $properties)
    {
        $filter = new PropertyFilter();
        
        return $this->matcher->match($filter, $item, $properties);
    }

    /**
     * @param array $dataArray
     * @param null|IItem $root
     * @return IItem
     */
    public function fromArray(array $dataArray, $root = null)
    {
        $propertyAccessor = PropertyAccess::getPropertyAccessor();

        $constructItem = function ($data) use ($propertyAccessor, &$constructItem) {
            $item = new $this->itemClass();

            $children = isset($data['children']) ? $data['children'] : [];
            unset($data['children']);

            foreach ($data as $property => $value) {
                $propertyAccessor->setValue($item, $property, $value);
            }
            foreach ($children as $childData) {
                $item->addChild($constructItem($childData));
            }

            return $item;
        };

        if (null === $root) {
            $root = new $this->itemClass();
        }

        foreach ($dataArray as $itemData) {
            $childItem = $constructItem($itemData);
            $root->addChild($childItem);
        }

        return $root;
    }

    /**
     * @param IItem $root
     * @return IItem
     */
    private function load(IItem $root = null)
    {
        if (null === $root) {
            $root = new $this->itemClass();
        }

        foreach ($this->loader->load($this->dataClass, $this->itemClass) as $item) {
            $root->addChild($item);
        }

        return $root;
    }
} 