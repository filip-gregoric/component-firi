<?php

namespace BWC\Component\FiriBundle\Loader;

use BWC\Component\FiriBundle\Component\IItem;
use BWC\Component\FiriBundle\Component\NestedItemInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * OrmLoader class
 *
 * @author Igor Pantović <php.igor@gmail.com>
 */
class OrmLoader implements ILoader
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param string $dataClass
     * @param string $itemClass
     * @return IItem[]
     */
    public function load($dataClass, $itemClass)
    {
        $builtItems = [];

        $getOrCreateitem = function (NestedItemInterface $item = null) use (&$builtItems, $itemClass) {
            if (null === $item) {
                return null;
            }
            $hash = spl_object_hash($item);
            if (!isset($builtItems[$hash])) {
                $builtItems[$hash] = new $itemClass();
            }

            return $builtItems[$hash];
        };

        /** @var NestedItemInterface[] $dataItems */
        $dataItems = $this->em->getRepository($dataClass)->findAll();

        $roots = [];
        foreach ($dataItems as $dataItem) {
            /** @var IItem $menuItem */
            $menuItem = new $itemClass();
            $menuItem->setData($dataItem);

            /** @var IItem $parent */
            if ($parent = $getOrCreateitem($dataItem->getParent())) {
                $parent->addChild($menuItem);
            } else {
                $roots[] = $menuItem;
            }
        }

        return $roots;
    }

    /**
     * @param string $itemClass
     * @return bool
     */
    public function supports($itemClass)
    {
        /** @var ClassMetadata[] $metadata */
        $metadata = $this->em->getMetadataFactory()->getAllMetadata();

        foreach ($metadata as $meta) {
            if ($meta->name == $itemClass) {
                return true;
            }
        }

        return false;
    }
} 