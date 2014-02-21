<?php

namespace BWC\Component\FiriBundle\Loader;

use BWC\Component\FiriBundle\Component\IItem;

/**
 * ILoader interface
 *
 * @author Igor Pantović <php.igor@gmail.com>
 */
interface ILoader
{
    /**
     * @param string $dataClass
     * @param string $itemClass
     * @return IItem[]
     */
    public function load($dataClass, $itemClass);

    /**
     * @param string $dataClass
     * @return bool
     */
    public function supports($dataClass);
}