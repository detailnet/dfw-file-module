<?php

namespace Detail\File\Factory\Repository;

use Zend\ServiceManager\ServiceLocatorInterface;

use Detail\File\BackgroundProcessing\Repository\RepositoryInterface as BackgroundProcessingRepositoryInterface;
use Detail\File\Exception\ConfigException;
use Detail\File\Factory\Resolver\ResolverFactoryInterface;
use Detail\File\Factory\Storage\StorageFactoryInterface;
use Detail\File\Options\Repository\RepositoryOptions;
use Detail\File\Options\ResolverOptions;
use Detail\File\Options\StorageOptions;
use Detail\File\Resolver\ResolverAwareInterface;
use Detail\File\Storage\StorageAwareInterface;

class RepositoryFactory implements
    RepositoryFactoryInterface
{
    /**
     * @var string
     */
    protected $repositoryClass = 'Detail\File\Repository\Repository';

    /**
     * {@inheritdoc}
     */
    public function getRepositoryClass()
    {
        return $this->repositoryClass;
    }

    /**
     * {@inheritdoc}
     */
    public function createRepository(ServiceLocatorInterface $serviceLocator, $name, array $config)
    {
        /** @var \Detail\File\Options\ModuleOptions $moduleOptions */
        $moduleOptions = $serviceLocator->get('Detail\File\Options\ModuleOptions');

        $storageFactories = $moduleOptions->getStorageFactories();
        $resolverFactories = $moduleOptions->getResolverFactories();

        $repositoryOptions = new RepositoryOptions($config);
        $repositoryClass = $this->getRepositoryClass();

        if (!class_exists($repositoryClass)) {
            throw new ConfigException(
                sprintf(
                    'Invalid save repository class "%s" specified in "class"; ' .
                    'must be a valid class name',
                    $repositoryClass
                )
            );
        }

        /** @todo Support derivatives */

        $storageOptions = $repositoryOptions->getStorage();

        if ($storageOptions === null) {
            throw new ConfigException('Missing configuration for storage');
        }

        $storage = $this->createStorage($serviceLocator, $storageFactories, $storageOptions);

        $repository = new $repositoryClass($name, $storage);

        if ($repository instanceof BackgroundProcessingRepositoryInterface) {
            /** @var \Detail\File\BackgroundProcessing\Driver\DriverInterface $driver */
            $driver = $serviceLocator->get($repositoryOptions->getBackgroundDriver());

            $repository->setBackgroundDriver($driver);
        }

        $resolverOptions = $repositoryOptions->getResolver();

        if ($resolverOptions !== null && $repository instanceof ResolverAwareInterface) {
            $resolver = $this->createResolver($serviceLocator, $resolverFactories, $resolverOptions);

            if ($resolver instanceof StorageAwareInterface) {
                $resolver->setStorage($storage);
            }

            $repository->setPublicUrlResolver($resolver);
        }

        return $repository;
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
        $factory = $serviceLocator->get($factoryClass);

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
        $factory = $serviceLocator->get($factoryClass);

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
