<?php

namespace Detail\File\Factory\BackgroundProcessing\Bernard\Receiver;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Detail\File\BackgroundProcessing\Bernard\Receiver\CreateItemReceiver;

class CreateItemReceiverFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Detail\File\Service\RepositoryService $repositories */
        $repositories = $serviceLocator->get('Detail\File\Service\RepositoryService');

        /** @var \Detail\File\BackgroundProcessing\Driver\Bernard\BernardDriver $bernardDriver */
        $bernardDriver = $serviceLocator->get(
            'Detail\File\BackgroundProcessing\Driver\Bernard\BernardDriver'
        );

        $service = new CreateItemReceiver($repositories, $bernardDriver->getMessenger());

        return $service;
    }
}
