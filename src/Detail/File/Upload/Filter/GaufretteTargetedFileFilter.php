<?php

namespace Detail\File\Upload\Filter;

use Detail\File\Storage\GaufretteStorage;

use Gaufrette\Exception as GaufretteException;
use Gaufrette\Filesystem;
use Gaufrette\File;

use Zend\Filter\Exception as FilterException;

class GaufretteTargetedFileFilter extends AbstractRenameFileFilter
{
    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @return Filesystem
     */
    public function getFilesystem()
    {
        return $this->filesystem;
    }

    /**
     * @param Filesystem $filesystem
     */
    public function setFilesystem($filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @param Filesystem $filesystem
     * @param array $options
     */
    public function __construct(Filesystem $filesystem, array $options = array())
    {
        parent::__construct($options);

        $this->setFilesystem($filesystem);
    }

    /**
     * {@inheritdoc}
     * @return File
     */
    protected function createFile($sourceFile, $targetFile, array $uploadData)
    {
        $filesystem = $this->getFilesystem();

        if ($filesystem->has($targetFile) && !$this->getOverwrite()) {
            throw new FilterException\InvalidArgumentException(
                sprintf("File '%s' could not be saved. It already exists.", $targetFile)
            );
        }

        // We can re-use our own GaufretteStorage for this...
        $storage = new GaufretteStorage($filesystem);
        $gaufretteFile = $storage->createFile($targetFile, $sourceFile, $uploadData);

        return $gaufretteFile;

//        /** @todo Populate with data */
//        $item = new Item();
//
//        return $item;
    }
}
