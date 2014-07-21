<?php

namespace Detail\File\Repository;

use Detail\File\Filesystem\FilesystemInterface;

class Repository implements
    RepositoryInterface
{
    protected $filesystem;

    public function __construct(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    protected function getFilesystem()
    {
        return $this->filesystem;
    }
}
