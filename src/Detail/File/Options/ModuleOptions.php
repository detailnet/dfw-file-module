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
     * @return RepositoryOptions[]
     */
    public function getRepositories()
    {
        return $this->repositories;
    }

    /**
     * @param RepositoryOptions[] $repositories
     */
    public function setRepositories($repositories)
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
    public function setDerivativeProviders($derivativeProviders)
    {
        $options = array();

        foreach ($derivativeProviders as $name => $config) {
            $options[$name] = new DerivativeProviderOptions($config);
        }

        $this->derivativeProviders = $options;
    }
}
