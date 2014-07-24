<?php

namespace Detail\File\Upload\Filter;

use Detail\File\Item\Item;

use Gaufrette\Exception as GaufretteException;
use Gaufrette\Filesystem;

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
     */
    protected function createFile($sourceFile, $targetFile, array $uploadData)
    {
        if ($this->getFilesystem()->has($targetFile) && !$this->getOverwrite()) {
            throw new FilterException\InvalidArgumentException(
                sprintf("File '%s' could not be saved. It already exists.", $targetFile)
            );
        }

        $file = $this->getFilesystem()->createFile($targetFile);
        $file->setName($uploadData['name']); // Original (not filtered) name

        if (isset($uploadData['size'])) {
            $file->setSize($uploadData['size']);
        }

        try {
            $file->setContent(
                file_get_contents($sourceFile),
                array('ContentType' => $uploadData['type']) /** @todo This is AwsS3 specific... */
            );
        } catch (\Exception $e) {
            throw new FilterException\RuntimeException(
                sprintf(
                    "File '%s' could not be saved. An error occurred while processing the file.",
                    $file->getKey()
                ), 0, $e
            );
        }

        $item = new Item();

        return $item;
    }
}
