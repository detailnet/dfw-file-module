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

        $createQueueOptions = $options->getCreateQueue();
        $completeQueueOptions = $options->getCompleteQueue();

        $driver = new BernardDriver($messenger);
        $driver->setCreateQueueName($createQueueOptions->getName());
        $driver->setCompleteQueueName($completeQueueOptions->getName());
        $driver->setQueueOptions(
            array(
                $createQueueOptions->getName() => $createQueueOptions->getOptions(),
                $completeQueueOptions->getName() => $completeQueueOptions->getOptions(),
            )
        );

        return $driver;
    }
}
