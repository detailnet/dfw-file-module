<?php

namespace Detail\File\Factory\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Detail\File\BackgroundProcessing\Repository\RepositoryInterface as BackgroundProcessingRepositoryInterface;
use Detail\File\Exception\ConfigException;
use Detail\File\Factory\Resolver\ResolverFactoryInterface;
use Detail\File\Resolver\ResolverAwareInterface;
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

        $resolverFactories = $options->getResolverFactories();

        /** @var \Detail\Gaufrette\Service\FilesystemService $filesystemService */
        $filesystemService = $serviceLocator->get('Detail\Gaufrette\Service\FilesystemService');

        $repositories = array();

        foreach ($options->getRepositories() as $name => $repositoryOptions) {
            /** @todo Allow for different storage types (see config) */
            /** @todo Support derivatives */

            $filesystem = $filesystemService->get($name);
            $storage = new GaufretteStorage($filesystem);

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

            /** @todo Support other implementations of RepositoryInterface */
            $repository = new $repositoryClass($name, $storage);

            if ($repository instanceof BackgroundProcessingRepositoryInterface) {
                /** @var \Detail\File\BackgroundProcessing\Driver\DriverInterface $driver */
                $driver = $serviceLocator->get($repositoryOptions->getBackgroundDriver());

                $repository->setBackgroundDriver($driver);
            }

            $resolver = $repositoryOptions->getResolver();

            if ($resolver !== null && $repository instanceof ResolverAwareInterface) {
                if (!isset($resolverFactories[$resolver->getType()])) {
                    throw new ConfigException(
                        sprintf('No factory configured for resolver type "%s"', $resolver->getType())
                    );
                }

                $resolverFactoryClass = $resolverFactories[$resolver->getType()];

                if (!class_exists($resolverFactoryClass)) {
                    throw new ConfigException(
                        sprintf(
                            'Resolver factory class "%s" does not exist for type "%s"',
                            $resolverFactoryClass, $resolver->getType()
                        )
                    );
                }

                $resolverFactory = new $resolverFactoryClass();

                if (!$resolverFactory instanceof ResolverFactoryInterface) {
                    throw new ConfigException(
                        sprintf(
                            'Invalid factory class "%s" configured for resolver type "%s";' .
                            'Expected Detail\File\Factory\Resolver\ResolverFactoryInterface object; received "%s"',
                            $resolverFactoryClass,
                            $resolver->getType(),
                            (is_object($resolverFactory) ? get_class($resolverFactory) : gettype($resolverFactory))
                        )
                    );
                }

                $repository->setPublicUrlResolver(
                    $resolverFactory->createResolver($serviceLocator, $resolver->getOptions())
                );
            }

            $repositories[$name] = $repository;
        }

        return new RepositoryService($repositories);
    }
}
