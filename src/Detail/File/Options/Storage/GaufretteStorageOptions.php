<?php

namespace Detail\File\Options\Storage;

use Detail\Core\Options\AbstractOptions;

class GaufretteStorageOptions extends AbstractOptions
{
    /**
     * @var string
     */
    protected $filesystem;

    /**
     * @return string
     */
    public function getFilesystem()
    {
        return $this->filesystem;
    }

    /**
     * @param string $filesystem
     */
    public function setFilesystem($filesystem)
    {
        $this->filesystem = $filesystem;
    }
}
