<?php

namespace Bwc\FiriBundle;

use Bwc\FiriBundle\Component\Iterator\BushIterator;
use Bwc\FiriBundle\Component\Iterator\BranchIterator;
use Bwc\FiriBundle\Loader\ILoader;
use Bwc\FiriBundle\Component\Exception\Exception;
use Bwc\FiriBundle\Component\IItem;

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
    private $itemClass;

    /**
     * @param string $itemClass
     * @param ILoader $loader
     * @param ProxyGenerator $proxyGenerator
     */
    public function __construct($itemClass, ILoader $loader, ProxyGenerator $proxyGenerator)
    {
        $this->itemClass = $itemClass;
        $this->loader = $loader;
        $this->proxyGenerator = $proxyGenerator;
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
     * @param IItem $child
     */
    public function addTo(IItem $item, IItem $child)
    {
        $item->addChild($child);
    }

    /**
     * @param IItem $item
     * @throws Exception
     */
    public function remove(IItem $item)
    {
        if (null === $item->getParent()) {
            throw new Exception('Root item can\'t be removed');
        }

        $item->getParent()->removeChild($item);
    }

    public function fromArray(array $array, $item = null)
    {
        throw new \Exception('Not implemented exception');
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

        foreach ($this->loader->load($this->itemClass) as $item) {
            $root->addChild($item);
        }

        return $root;
    }
} 