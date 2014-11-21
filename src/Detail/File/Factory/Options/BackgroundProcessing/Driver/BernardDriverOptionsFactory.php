<?php

namespace Detail\File\Factory\Options\BackgroundProcessing\Driver;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Detail\File\Exception\ConfigException;
use Detail\File\Options\BackgroundProcessing\Driver\BernardDriverOptions;

class BernardDriverOptionsFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     * @return BernardDriverOptions
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Detail\File\Options\ModuleOptions $moduleOptions */
        $moduleOptions = $serviceLocator->get('Detail\File\Options\ModuleOptions');
        $drivers = $moduleOptions->getBackgroundDrivers();

        if (!isset($drivers['bernard'])) {
            throw new ConfigException(
                'Config for driver Detail\File\BackgroundProcessing\Driver\BernardDriver is not set'
            );
        }

        return new BernardDriverOptions($drivers['bernard']);
    }
}
