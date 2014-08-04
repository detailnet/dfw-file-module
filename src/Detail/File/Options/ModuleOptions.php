<?php

namespace Detail\File\Options;

use Detail\Core\Options\AbstractOptions;

class ModuleOptions extends AbstractOptions
{
    /**
     * @var RepositoryOptions[]
     */
    protected $repositories = array();

    /**
     * @var DerivativeProviderOptions[]
     */
    protected $derivativeProviders = array();

    /**
     * @var array
     */
    protected $backgroundDrivers = array();

    /**
     * @var array
     */
    protected $resolverFactories = array();

    /**
     * @return RepositoryOptions[]
     */
    public function getRepositories()
    {
        return $this->repositories;
    }

    /**
     * @param RepositoryOptions[] $repositories
     */
    public function setRepositories(array $repositories)
    {
        $options = array();

        foreach ($repositories as $name => $config) {
            $options[$name] = new RepositoryOptions($config);
        }

        $this->repositories = $options;
    }

    /**
     * @return DerivativeProviderOptions[]
     */
    public function getDerivativeProviders()
    {
        return $this->derivativeProviders;
    }

    /**
     * @param DerivativeProviderOptions[] $derivativeProviders
     */
    public function setDerivativeProviders(array $derivativeProviders)
    {
        $options = array();

        foreach ($derivativeProviders as $name => $config) {
            $options[$name] = new DerivativeProviderOptions($config);
        }

        $this->derivativeProviders = $options;
    }

    /**
     * @return array
     */
    public function getBackgroundDrivers()
    {
        return $this->backgroundDrivers;
    }

    /**
     * @param array $backgroundDrivers
     */
    public function setBackgroundDrivers(array $backgroundDrivers)
    {
        $this->backgroundDrivers = $backgroundDrivers;
    }

    /**
     * @return array
     */
    public function getResolverFactories()
    {
        return $this->resolverFactories;
    }

    /**
     * @param array $resolverFactories
     */
    public function setResolverFactories(array $resolverFactories)
    {
        $this->resolverFactories = $resolverFactories;
    }
}
