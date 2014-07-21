<?php

namespace Detail\File\Service;

use Detail\File\Repository\RepositoryInterface;

use InvalidArgumentException;

class RepositoryService
{
    /**
     * @var RepositoryInterface[]
     */
    protected $repositories = array();

    /**
     * Instantiate a new repository service.
     *
     * @param RepositoryInterface[] $repositories
     * @throws InvalidArgumentException
     */
    public function __construct(array $repositories)
    {
        // Check all repositories first before adding anything...
        foreach ($repositories as $repository) {
            if (!$repository instanceof RepositoryInterface) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Detail\File\Service\RepositoryService only accepts repository objects of type %s; received "%s"',
                        'Detail\File\Repository\RepositoryInterface',
                        (is_object($repository) ? get_class($repository) : gettype($repository))
                    )
                );
            }
        }

        $this->repositories = $repositories;
    }

    /**
     * {@inheritdoc}
     */
    public function add($name, RepositoryInterface $repository)
    {
        if ($this->has($name)) {
            throw new InvalidArgumentException(
                sprintf('Repository already registered for name "%s"', $name)
            );
        }

        $this->repositories[$name] = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function has($name)
    {
        return isset($this->repositories[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function get($name)
    {
        if (!$this->has($name)) {
            throw new InvalidArgumentException(
                sprintf('No repository registered for name "%s"', $name)
            );
        }

        return $this->repositories[$name];
    }

    /**
     * {@inheritdoc}
     */
    public function getAll()
    {
        return $this->repositories;
    }
}
