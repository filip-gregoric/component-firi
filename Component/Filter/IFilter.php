<?php

namespace BWC\Component\FiriBundle\Component\Filter;

use BWC\Component\FiriBundle\Component\IItem;

interface IFilter
{
    public function filter(IItem $item, array $options = array());

    public function getName();
}