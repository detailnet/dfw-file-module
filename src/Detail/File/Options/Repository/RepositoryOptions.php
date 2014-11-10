<?php

namespace Detail\File\Options\Repository;

use Detail\Core\Options\AbstractOptions;
use Detail\File\Options\StorageOptions;
use Detail\File\Options\ResolverOptions;

class RepositoryOptions extends AbstractOptions
{
    /**
     * @var StorageOptions
     */
    protected $storage;

    /**
     * @var ResolverOptions
     */
    protected $resolver;

    /**
     * @var string
     */
    protected $backgroundDriver = 'Detail\File\BackgroundProcessing\Driver\Bernard\BernardDriver';

    /**
     * @var array
     */
    protected $derivatives = array();

    /**
     * @var int
     */
    protected $keepRevisions = 0;

    /**
     * @param array $storage
     */
    public function setStorage(array $storage)
    {
        $this->storage = new StorageOptions($storage);
    }

    /**
     * @return StorageOptions
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * @return ResolverOptions
     */
    public function getResolver()
    {
        return $this->resolver;
    }

    /**
     * @param array $resolver
     */
    public function setResolver(array $resolver)
    {
        $this->resolver = new ResolverOptions($resolver);
    }

    /**
     * @return string
     */
    public function getBackgroundDriver()
    {
        return $this->backgroundDriver;
    }

    /**
     * @param string $backgroundDriver
     */
    public function setBackgroundDriver($backgroundDriver)
    {
        $this->backgroundDriver = $backgroundDriver;
    }

    /**
     * @param array $derivatives
     */
    public function setDerivatives(array $derivatives)
    {
        $this->derivatives = $derivatives;
    }

    /**
     * @return array
     */
    public function getDerivatives()
    {
        return $this->derivatives;
    }

    /**
     * @param int $keepRevisions
     */
    public function setKeepRevisions($keepRevisions)
    {
        $this->keepRevisions = $keepRevisions;
    }

    /**
     * @return int
     */
    public function getKeepRevisions()
    {
        return $this->keepRevisions;
    }
}
