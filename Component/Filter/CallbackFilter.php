<?php

namespace BWC\Component\FiriBundle\Component\Filter;

use BWC\Component\FiriBundle\Component\IItem;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * CallbackFilter class
 *
 * @author Igor Pantović <php.igor@gmail.com>
 */
class CallbackFilter implements IFilter
{
    /**
     * @var OptionsResolver
     */
    protected $resolver;

    public function __construct()
    {
        $this->setUpResolver();
    }

    /**
     * {@inheritDoc}
     */
    public function filter(IItem $item, array $options = array())
    {
        // Validate options passed
        $this->resolver->resolve($options);

        return $options['callback']($item);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'callback';
    }

    /**
     * Sets up resolver.
     */
    private function setUpResolver()
    {
        $this->resolver = new OptionsResolver;

        $this->resolver->setRequired(
            array(
                'callback',
            )
        );

        $this->resolver->setAllowedTypes(
            array(
                'callback' => 'callable',
            )
        );
    }
}