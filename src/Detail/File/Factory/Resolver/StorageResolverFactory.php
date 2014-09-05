<?php

namespace Detail\File\Factory\Resolver;

use Zend\ServiceManager\ServiceLocatorInterface;

use Detail\File\Resolver\StorageResolver as Resolver;
//use Detail\File\Options\Resolver\StorageResolverOptions as Options;

class StorageResolverFactory implements
    ResolverFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createResolver(ServiceLocatorInterface $serviceLocator, array $config)
    {
//        $options = new Options($config);
        $resolver = new Resolver();

        return $resolver;
    }
}
