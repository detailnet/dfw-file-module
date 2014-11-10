<?php

namespace Detail\File\Factory\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use ProxyManager\Factory\LazyLoadingValueHolderFactory;
use ProxyManager\Proxy\LazyLoadingInterface;

use Detail\File\Exception\ConfigException;
use Detail\File\Factory\Repository\RepositoryFactoryInterface;
use Detail\File\Options\RepositoryOptions;
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

        $repositoryFactories = $moduleOptions->getRepositoryFactories();

        $repositories = array();

        foreach ($moduleOptions->getRepositories() as $name => $options) {
            $repositories[$name] = $this->createRepository($serviceLocator, $repositoryFactories, $name, $options);
        }

        return new RepositoryService($repositories);
    }

    protected function createRepository(ServiceLocatorInterface $serviceLocator, array $factories, $name, RepositoryOptions $repository)
    {
        if (!isset($factories[$repository->getType()])) {
            throw new ConfigException(
                sprintf('No factory configured for repository type "%s"', $repository->getType())
            );
        }

        $factoryClass = $factories[$repository->getType()];
        $factory = $serviceLocator->get($factoryClass);

        if (!$factory instanceof RepositoryFactoryInterface) {
            throw new ConfigException(
                sprintf(
                    'Invalid factory class "%s" configured for repository type "%s";' .
                    'Expected Detail\File\Factory\Repository\RepositoryFactoryInterface object; received "%s"',
                    $factoryClass,
                    $repository->getType(),
                    (is_object($factory) ? get_class($factory) : gettype($factory))
                )
            );
        }

        if ($repository->getUseProxy()) {
            $lazyLoadingFactory = new LazyLoadingValueHolderFactory();
            $initializer = function (& $wrappedObject, LazyLoadingInterface $proxy, $method, array $parameters, & $initializer) use (
                $factory, $serviceLocator, $name, $repository
            ) {
                $initializer   = null; // disable initialization
                $wrappedObject = $factory->createRepository($serviceLocator, $name, $repository->getOptions());

                return true; // confirm that initialization occurred correctly
            };

            return $lazyLoadingFactory->createProxy($factory->getRepositoryClass(), $initializer);
        } else {
            return $factory->createRepository($serviceLocator, $name, $repository->getOptions());
        }
    }
}
