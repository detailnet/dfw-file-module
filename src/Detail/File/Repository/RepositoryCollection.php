<?php

namespace Detail\File\Repository;

use ArrayIterator;

use Detail\File\Exception\InvalidArgumentException;
use Detail\File\Exception\RepositoryExistsException;
use Detail\File\Exception\RepositoryNotFoundException;

class RepositoryCollection implements
    RepositoryCollectionInterface
{
    /**
     * @var RepositoryInterface[]
     */
    protected $repositories = array();

    /**
     * Instantiate a new repository collection.
     *
     * @param RepositoryInterface[] $repositories
     * @throws InvalidArgumentException
     */
    public function __construct(array $repositories)
    {
        // Check all repositories first before adding anything...
        foreach ($repositories as $name => $repository) {
            if (!$repository instanceof RepositoryInterface) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Detail\File\Service\RepositoryService only accepts repository objects of type' .
                        'Detail\File\Repository\RepositoryInterface; received "%s"',
                        (is_object($repository) ? get_class($repository) : gettype($repository))
                    )
                );
            }

            $this->add($name, $repository);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function add($name, RepositoryInterface $repository)
    {
        $this->offsetSet($name, $repository);
    }

    /**
     * {@inheritdoc}
     */
    public function has($nameOrRepository)
    {
        $this->offsetExists($nameOrRepository);
    }

    /**
     * {@inheritdoc}
     */
    public function get($nameOrRepository)
    {
        $this->offsetGet($nameOrRepository);
    }

    /**
     * {@inheritdoc}
     */
    public function getAll()
    {
        return $this->repositories;
    }

    /**
     * {@inheritdoc}
     */
    public function getCollection($namesOrRepositories)
    {
        if (!is_array($namesOrRepositories)) {
            $namesOrRepositories = array($namesOrRepositories);
        }

        $repositories = array();

        foreach ($namesOrRepositories as $nameOrRepository) {
            $repositories = $this->get($nameOrRepository);
        }

        return new self($repositories);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($nameOrRepository)
    {
        $this->offsetUnset($nameOrRepository);
    }

    /**
     * {@inheritdoc}
     */
    public function removeAll()
    {
        $this->repositories = array();
    }

    public function getIterator()
    {
        return new ArrayIterator($this->repositories);
    }

    public function offsetExists($nameOrRepository)
    {
        try {
            $this->get($nameOrRepository);
            return true;

        } catch (RepositoryNotFoundException $e) {
            return false;
        }
    }

    public function offsetGet($nameOrRepository)
    {
        if ($nameOrRepository instanceof RepositoryInterface) {
            return $this->getByRepository($nameOrRepository);
        } else if (is_string($nameOrRepository)) {
            return $this->getByName($nameOrRepository);
        } else {
            $this->throwInvalidRepositoryArgumentException($nameOrRepository);
        }
    }

    public function offsetSet($name, $repository)
    {
        $this->assertNameIsString($name);

        if ($this->has($name)) {
            throw new RepositoryExistsException(
                sprintf('Repository "%s" already exists; choose a different name', $name)
            );
        }

        $this->repositories[$name] = $repository;
    }

    public function offsetUnset($nameOrRepository)
    {
        // Note that we're not failing if the repository does not exist.

        if ($nameOrRepository instanceof RepositoryInterface) {
            $this->repositories = array_filter(
                $this->repositories,
                function (RepositoryInterface $repository) use ($nameOrRepository) {
                    return $nameOrRepository !== $repository;
                }
            );
        } else if (is_string($nameOrRepository)) {
            unset($this->repositories[$nameOrRepository]);
        } else {
            $this->throwInvalidRepositoryArgumentException($nameOrRepository);
        }
    }

    public function count()
    {
        return count($this->repositories);
    }

    protected function getByName($name)
    {
        $this->assertNameIsString($name);

        if (isset($this->repositories[$name])) {
            return $this->repositories[$name];
        }

        throw new RepositoryNotFoundException(
            sprintf('Repository "%s" does not exist in this collection', $name)
        );
    }

    protected function getByRepository(RepositoryInterface $repository)
    {
        $this->getNameByRepository($repository); // Just to check if it exists
        return $repository;
    }

    protected function getNameByRepository(RepositoryInterface $repository)
    {
        foreach ($this->repositories as $name => $existingRepository) {
            if ($repository === $existingRepository) {
                return $name;
            }
        }

        throw new RepositoryNotFoundException('Repository does not exist in this collection');
    }

    protected function assertNameIsString($name)
    {
        if (!is_string($name)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Expected string as name for the repository; received "%s"',
                    (is_object($name) ? get_class($name) : gettype($name))
                )
            );
        }
    }

    protected function throwInvalidRepositoryArgumentException($nameOrRepository)
    {
        throw new InvalidArgumentException(
            sprintf(
                'Expected object of type Detail\File\Repository\RepositoryInterface ' .
                'or string as identifier for the repository; received "%s"',
                (is_object($nameOrRepository) ? get_class($nameOrRepository) : gettype($nameOrRepository))
            )
        );
    }
}
