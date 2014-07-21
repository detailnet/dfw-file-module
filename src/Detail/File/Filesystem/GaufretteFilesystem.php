<?php

namespace Detail\File\Filesystem;

use Gaufrette\Filesystem;

class GaufretteFilesystem implements
    FilesystemInterface
{
    protected $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    protected function getFilesystem()
    {
        return $this->filesystem;
    }
} 
