<?php

namespace Detail\File\Factory\BackgroundProcessing\Driver;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Detail\File\BackgroundProcessing\Driver\Bernard\BernardDriver;

class BernardDriverFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Detail\File\Options\BackgroundProcessing\Driver\BernardDriverOptions $options */
        $options = $serviceLocator->get(
            'Detail\File\Options\BackgroundProcessing\Driver\BernardDriverOptions'
        );

        /** @var \Detail\Bernard\Message\Messenger $messenger */
        $messenger = $serviceLocator->get($options->getMessenger());

        $driver = new BernardDriver($messenger);
        $driver->setCreateQueueName($options->getCreateQueueName());
        $driver->setCompleteQueueName($options->getCompleteQueueName());

        return $driver;
    }
}
