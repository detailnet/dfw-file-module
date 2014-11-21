<?php

namespace Detail\File\Factory\Resolver;

use Zend\ServiceManager\ServiceLocatorInterface;

use Detail\File\Resolver\PrefixResolver as Resolver;
use Detail\File\Options\Resolver\PrefixResolverOptions as Options;

class PrefixResolverFactory implements
    ResolverFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createResolver(ServiceLocatorInterface $serviceLocator, array $config)
    {
        $options = new Options($config);
        $resolver = new Resolver($options->getPrefix());

        return $resolver;
    }
}
