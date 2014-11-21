<?php

namespace Detail\File\Factory\Resolver;

use Zend\ServiceManager\ServiceLocatorInterface;

interface ResolverFactoryInterface
{
    /**
     * Create resolver.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param array $config
     * @return \Detail\File\Resolver\ResolverInterface
     */
    public function createResolver(ServiceLocatorInterface $serviceLocator, array $config);
} 
