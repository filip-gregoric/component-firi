<?php

namespace Bwc\FiriBundle\Loader;

use Bwc\FiriBundle\Component\IItem;

/**
 * ILoader interface
 *
 * @author Igor Pantović <php.igor@gmail.com>
 */
interface ILoader
{
    /**
     * @param string $itemClass
     * @return IItem[]
     */
    public function load($itemClass);

    /**
     * @param string $itemClass
     * @return bool
     */
    public function supports($itemClass);
}