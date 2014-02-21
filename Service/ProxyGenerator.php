<?php

namespace BWC\Component\FiriBundle\Service;

use BWC\Component\FiriBundle\Component\Iterator\IIterator;
use BWC\Component\FiriBundle\Component\IItem;

/**
 * ProxyGenerator class.
 *
 * Used to generate fake item structure from iterators.
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
            /** @var $item IItem */
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