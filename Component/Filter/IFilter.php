<?php

namespace BWC\FiriBundle\Component\Filter;

use BWC\FiriBundle\Component\IItem;

interface IFilter
{
    public function filter(IItem $item, array $options = array());

    public function getName();
}