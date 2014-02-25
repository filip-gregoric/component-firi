<?php

namespace BWC\Component\FiriBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class BWCFiriExtension extends Extension
{
    public function load(array $config, ContainerBuilder $container)
    {
        $locator = new FileLocator(__DIR__ . '/../Resources/config');
        $loader = new YamlFileLoader($container, $locator);

        $loader->load('services.yml');

        $firiLoaderIds = array_keys($container->findTaggedServiceIds('firi.loader'));

        $factoryDefinition = $container->getDefinition('bwc_firi.firi_factory');
        foreach ($firiLoaderIds as $firiLoaderId) {
            $factoryDefinition->addMethodCall('registerLoader', [new Reference($firiLoaderId)]);
        }
    }
} 