<?php

namespace Detail\File\Storage;

use Gaufrette\Filesystem;

class GaufretteStorage implements
    StorageInterface
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
