<?php

namespace BWC\Component\FiriBundle\Service;

use BWC\Component\FiriBundle\Firi;
use BWC\Component\FiriBundle\Loader\ILoader;
use BWC\Component\FiriBundle\Component\Exception\Exception;

/**
 * FiriFactory class
 *
 * @author Igor Pantović <php.igor@gmail.com>
 */
class FiriFactory
{
    /**
     * @var ILoader[]
     */
    private $loaders = [];

    /**
     * @var ProxyGenerator
     */
    private $proxyGenerator;

    /**
     * @param ProxyGenerator $proxyGenerator
     */
    public function __construct(ProxyGenerator $proxyGenerator)
    {
        $this->proxyGenerator = $proxyGenerator;
    }

    /**
     * @param ILoader $loader
     */
    public function registerLoader(ILoader $loader)
    {
        $this->loaders[] = $loader;
    }

    /**
     * @param string $dataClass Must implement NestedItemInterface
     * @param string $itemClass Must implement IItem interface
     * @return Firi
     */
    public function create($dataClass, $itemClass)
    {
        $loader = $this->findLoader($dataClass);
        $firi = new Firi($dataClass, $itemClass, $loader, $this->proxyGenerator);

        return $firi;
    }

    /**
     * Selects loader that supports class
     *
     * @param string $itemClass
     * @return ILoader
     * @throws Exception If no loader supports class
     */
    private function findLoader($itemClass)
    {
        foreach ($this->loaders as $loader) {
            if ($loader->supports($itemClass)) {
                return $loader;
            }
        }

        throw new Exception(sprintf(
            'No loader supports "%s"',
            $itemClass
        ));
    }
} 