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

        /** @var \Detail\Bernard\Message\Messenger $messenger */
        /** @todo This does not obviously work (repository > backend driver > messenger) */
        $messenger = $serviceLocator->get('Detail\Bernard\Message\Messenger');

        $service = new CreateItemReceiver($repositories, $messenger);

        return $service;
    }
}
