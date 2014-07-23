<?php

namespace Detail\File\Repository;

use Detail\File\Storage\StorageInterface;

class Repository implements
    RepositoryInterface
{
    protected $storage;

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    protected function getStorage()
    {
        return $this->storage;
    }
}
