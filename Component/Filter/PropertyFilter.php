<?php

namespace BWC\Component\FiriBundle\Component\Filter;

use BWC\Component\FiriBundle\Component\IItem;

class PropertyFilter implements IFilter
{
    public function filter(IItem $item, array $options = array())
    {
        foreach ($options as $field => $val) {
            if ($field != $val) {
                return false;
            }
        }
        return true;
    }

    public function getName()
    {
        return 'property';
    }
} 