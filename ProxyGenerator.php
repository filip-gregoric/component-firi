<?php

namespace Bwc\FiriBundle;

use Bwc\FiriBundle\Component\Iterator\IIterator;
use Bwc\FiriBundle\Component\Item;
use Bwc\FiriBundle\Component\IItem;

/**
 * ProxyGenerator class.
 *
 * Used to generate fake item structure from iterators and
 * inject custom item attributes.
 *
 * @author Igor Pantović <php.igor@gmail.com>
 */
class ProxyGenerator
{
    /**
     * Builds fake item structure by looping through iterator.
     *
     * @param IIterator $iterator
     * @param IItem $root
     *
     * @return IItem
     */
    public function getProxy(IIterator $iterator, IItem $root)
    {
        $depth = 0;

        // Set iterator callbacks
        $stepDown = function () use (&$depth) {
            $depth++;
        };

        $stepUp = function () use (&$depth) {
            $depth--;
        };

        $iterator->setDescendCallback($stepDown);
        $iterator->setAscendCallback($stepUp);

        // Create root item
        $proxyRoot = $root->isolatedClone();

        // Setup loop starting values
        $currentItem = $proxyRoot;
        $lastItem    = $proxyRoot;
        $lastDepth   = $depth;
        foreach ($iterator as $item) {
            /** @var $item \Bwc\FiriBundle\Component\IItem */
            $proxy = $item->isolatedClone();

            // Descended
            if ($lastDepth < $depth) {
                $currentItem = $lastItem;
            }

            // Ascended
            if ($lastDepth > $depth) {
                for ($i = 0; $i < $lastDepth - $depth; $i++) {
                    $currentItem = $currentItem->getParent();
                }
            }

            $currentItem->addChild($proxy);

            $lastItem  = $proxy;
            $lastDepth = $depth;
        }

        return $proxyRoot;
    }
}