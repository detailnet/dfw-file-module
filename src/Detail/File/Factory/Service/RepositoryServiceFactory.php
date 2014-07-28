<?php

namespace Detail\File\Factory\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Detail\Bernard\Message\Messenger;
use Detail\File\Exception\ConfigException;
use Detail\File\BackgroundProcessing\Driver\Bernard\BernardDriver;
use Detail\File\BackgroundProcessing\Message\MessageFactory;
use Detail\File\Repository\Repository;
use Detail\File\Storage\GaufretteStorage;
use Detail\File\Service\RepositoryService;

class RepositoryServiceFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     * @return RepositoryService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Detail\File\Options\ModuleOptions $options */
        $options = $serviceLocator->get('Detail\File\Options\ModuleOptions');

//        $adapterFactories = $options->getAdapterFactories();
//        $adapters = array();
//
//        foreach ($options->getAdapters() as $name => $adapter) {
//            /** @var \Detail\Gaufrette\Options\AdapterOptions $adapter */
//
//            if (!isset($adapterFactories[$adapter->getType()])) {
//                throw new ConfigException(
//                    sprintf('No factory configured for adapter type "%s"', $adapter->getType())
//                );
//            }
//
//            $adapterFactoryClass = $adapterFactories[$adapter->getType()];
//
//            if (!class_exists($adapterFactoryClass)) {
//                throw new ConfigException(
//                    sprintf(
//                        'Adapter factory class "%s" does not exist for type "%s"',
//                        $adapterFactoryClass, $adapter->getType()
//                    )
//                );
//            }
//
//            /** @var \Detail\Gaufrette\Factory\Adapter\AdapterInterface $adapterFactory */
//            $adapterFactory = new $adapterFactoryClass();
//
//            $adapters[$name] = $adapterFactory->createAdapter(
//                $serviceLocator, $adapter->getOptions()
//            );
//        }

        /** @var \Detail\Gaufrette\Service\FilesystemService $filesystemService */
        $filesystemService = $serviceLocator->get('Detail\Gaufrette\Service\FilesystemService');

        $repositories = array();

        foreach ($options->getRepositories() as $name => $repositoryOptions) {
            /** @todo Allow for different storage types (see config) */
            /** @todo Support derivatives */

            $filesystem = $filesystemService->get($name);
            $storage = new GaufretteStorage($filesystem);

            $messageFactory = new MessageFactory();
            /** @var \Bernard\Producer $producer */
            $producer = $serviceLocator->get('Bernard\Producer');
            $messenger = new Messenger($producer, $messageFactory);
            $driver = new BernardDriver($messenger); /** @todo Provide queue names */

            $repository = new Repository($name, $storage);
            $repository->setBackgroundDriver($driver);

            $repositories[$name] = $repository;
        }

        return new RepositoryService($repositories);
    }
}
