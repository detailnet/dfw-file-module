<?php

namespace Detail\File\Factory\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Detail\File\BackgroundProcessing\Repository\RepositoryInterface as BackgroundProcessingRepositoryInterface;
use Detail\File\Exception\ConfigException;
use Detail\File\Factory\Resolver\ResolverFactoryInterface;
use Detail\File\Factory\Storage\StorageFactoryInterface;
use Detail\File\Options\ResolverOptions;
use Detail\File\Options\StorageOptions;
use Detail\File\Resolver\ResolverAwareInterface;
use Detail\File\Service\RepositoryService;

class RepositoryServiceFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     * @return RepositoryService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Detail\File\Options\ModuleOptions $moduleOptions */
        $moduleOptions = $serviceLocator->get('Detail\File\Options\ModuleOptions');

        $storageFactories = $moduleOptions->getStorageFactories();
        $resolverFactories = $moduleOptions->getResolverFactories();

        $repositories = array();

        foreach ($moduleOptions->getRepositories() as $name => $repositoryOptions) {
            /** @todo Support derivatives */

            $repositoryClass = $repositoryOptions->getClass();

            if (!class_exists($repositoryClass)) {
                throw new ConfigException(
                    sprintf(
                        'Invalid save repository class "%s" specified in "class"; ' .
                        'must be a valid class name',
                        $repositoryClass
                    )
                );
            }

            $storage = $repositoryOptions->getStorage();

            if ($storage === null) {
                throw new ConfigException('Missing configuration for storage');
            }

            /** @todo Support other implementations of RepositoryInterface */
            $repository = new $repositoryClass(
                $name,
                $this->createStorage($serviceLocator, $storageFactories, $storage)
            );

            if ($repository instanceof BackgroundProcessingRepositoryInterface) {
                /** @var \Detail\File\BackgroundProcessing\Driver\DriverInterface $driver */
                $driver = $serviceLocator->get($repositoryOptions->getBackgroundDriver());

                $repository->setBackgroundDriver($driver);
            }

            $resolver = $repositoryOptions->getResolver();

            if ($resolver !== null && $repository instanceof ResolverAwareInterface) {
                $repository->setPublicUrlResolver(
                    $this->createResolver($serviceLocator, $resolverFactories, $resolver)
                );
            }

            $repositories[$name] = $repository;
        }

        return new RepositoryService($repositories);
    }

    protected function createStorage(
        ServiceLocatorInterface $serviceLocator, array $factories, StorageOptions $storage
    ) {
        if (!isset($factories[$storage->getType()])) {
            throw new ConfigException(
                sprintf('No factory configured for storage type "%s"', $storage->getType())
            );
        }

        $factoryClass = $factories[$storage->getType()];

        if (!class_exists($factoryClass)) {
            throw new ConfigException(
                sprintf(
                    'Storage factory class "%s" does not exist for type "%s"',
                    $factoryClass, $storage->getType()
                )
            );
        }

        $factory = new $factoryClass();

        if (!$factory instanceof StorageFactoryInterface) {
            throw new ConfigException(
                sprintf(
                    'Invalid factory class "%s" configured for storage type "%s";' .
                    'Expected Detail\File\Factory\Storage\StorageFactoryInterface object; received "%s"',
                    $factoryClass,
                    $storage->getType(),
                    (is_object($factory) ? get_class($factory) : gettype($factory))
                )
            );
        }

        return $factory->createStorage($serviceLocator, $storage->getOptions());
    }

    protected function createResolver(
        ServiceLocatorInterface $serviceLocator, array $factories, ResolverOptions $resolver
    ) {
        if (!isset($factories[$resolver->getType()])) {
            throw new ConfigException(
                sprintf('No factory configured for resolver type "%s"', $resolver->getType())
            );
        }

        $factoryClass = $factories[$resolver->getType()];

        if (!class_exists($factoryClass)) {
            throw new ConfigException(
                sprintf(
                    'Resolver factory class "%s" does not exist for type "%s"',
                    $factoryClass, $resolver->getType()
                )
            );
        }

        $factory = new $factoryClass();

        if (!$factory instanceof ResolverFactoryInterface) {
            throw new ConfigException(
                sprintf(
                    'Invalid factory class "%s" configured for resolver type "%s";' .
                    'Expected Detail\File\Factory\Resolver\ResolverFactoryInterface object; received "%s"',
                    $factoryClass,
                    $resolver->getType(),
                    (is_object($factory) ? get_class($factory) : gettype($factory))
                )
            );
        }

        return $factory->createResolver($serviceLocator, $resolver->getOptions());
    }
}
