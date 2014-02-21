<?php

namespace Bwc\FiriBundle\Loader;

use Bwc\FiriBundle\Component\IItem;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Mapping\ClassMetadata;

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
     * @param string $itemClass
     * @return IItem[]
     */
    public function load($itemClass)
    {
        /** @var IItem[] $items */
        $items = $this->em->getRepository($itemClass)->findAll();

        $roots = [];
        foreach ($items as $item) {
            if ($parent = $item->getParent()) {
                $parent->addChild($item);
            } else {
                $roots[] = $item;
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