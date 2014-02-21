<?php

namespace BWC\Component\FiriBundle\Component;

interface NestedItemInterface
{
    /**
     * @return object|null
     */
    public function getParent();
} 