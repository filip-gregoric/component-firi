<?php

namespace Bwc\FiriBundle;

use Bwc\FiriBundle\Loader\ILoader;
use Bwc\FiriBundle\Component\Exception\Exception;

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
     * @param string $itemClass
     * @return Firi
     */
    public function create($itemClass)
    {
        $loader = $this->findLoader($itemClass);
        $firi = new Firi($itemClass, $loader, $this->proxyGenerator);

        return $firi;
    }

    /**
     * Selects loader that supports class
     *
     * @param string $itemClass
     * @return ILoader
     * @throws \BWC\FiriBundle\Component\Exception\Exception If no loader supports class
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