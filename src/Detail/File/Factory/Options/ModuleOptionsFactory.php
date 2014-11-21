<?php

namespace Detail\File\Factory\Options;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Detail\File\Exception\ConfigException;
use Detail\File\Options\ModuleOptions;

class ModuleOptionsFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     * @return ModuleOptions
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');

        if (!isset($config['detail_file'])) {
            throw new ConfigException('Config for Detail\File is not set');
        }

        return new ModuleOptions($config['detail_file']);
    }
}
