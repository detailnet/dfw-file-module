<?php

namespace Detail\File\Options;

use Detail\Core\Options\AbstractOptions;

class RepositoryOptions extends AbstractOptions
{
    /**
     * @var string
     */
    protected $class = 'Detail\File\Repository\Repository';

    /**
     * @var string
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
     * @param string $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param string $storage
     */
    public function setStorage($storage)
    {
        $this->storage = $storage;
    }

    /**
     * @return string
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
