<?php

namespace Detail\File\Factory\Storage;

use Zend\ServiceManager\ServiceLocatorInterface;

interface StorageFactoryInterface
{
    /**
     * Create storage.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param array $config
     * @return \Detail\File\Storage\StorageInterface
     */
    public function createStorage(ServiceLocatorInterface $serviceLocator, array $config);
} 
