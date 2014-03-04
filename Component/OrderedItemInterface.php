<?php

namespace BWC\Component\FiriBundle\Component;

interface OrderedItemInterface
{
    /**
     * Items with lowest order will be before ones with higher
     *
     * @return int
     */
    public function getOrder();
} 