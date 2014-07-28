<?php

namespace Detail\File\Storage;

use Detail\File\Exception\RuntimeException;
use Detail\File\Exception\StorageException;
use Detail\File\Item\Item;
use Detail\File\Item\ItemInterface;
use Detail\File\Repository\RepositoryInterface;

use Gaufrette\Adapter;
//use Gaufrette\Exception as GaufretteException;
use Gaufrette\Filesystem;

class GaufretteStorage implements
    StorageInterface
{
    protected $filesystem;

    protected $urlProvider;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    protected function getFilesystem()
    {
        return $this->filesystem;
    }

    public function hasItem($id, $revision = null)
    {
        return $this->getFilesystem()->has($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getItem(RepositoryInterface $repository, $id, $revision = null)
    {
        if (!$this->hasItem($id)) {
            return null;
        }

//        try {
//            $this->getFilesystem()->get($id);
//        } catch (GaufretteException\FileNotFound $e) {
//            return null;
//        }

        return new Item($repository, $id);
    }

    /**
     * {@inheritdoc}
     */
    public function getItemContents($id, $revision = null)
    {
        return $this->getFilesystem()->read($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getItemMeta($id, $revision = null)
    {
        /** @todo Not yet saved... */
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function getItemName($id, $revision = null)
    {
        return basename($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getItemType($id, $revision = null)
    {
        return $this->getFilesystem()->mimeType($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getItemSize($id, $revision = null)
    {
        return $this->getFilesystem()->size($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getItemPublicUrl($id, $revision = null)
    {
        $adapter = $this->getFilesystem()->getAdapter();

        if ($adapter instanceof Adapter\AwsS3) {
            $url = $adapter->getUrl($id);
        } else if ($adapter instanceof Adapter\Local) {
            /** @todo Create and use URL provider/factory (pass adapter) */
            $url = '/file/download/user-images/' . $id;
        } else {
            throw new RuntimeException(
                sprintf('Adapter %s does not provide public URLs', get_class($adapter))
            );
        }

        return $url;
    }

    /**
     * {@inheritdoc}
     */
    public function createItem(RepositoryInterface $repository, $id, $file, array $meta = array())
    {
        /** @todo Work with stream */
        return $this->createItemFromContents($repository, $id, $this->getFileContents($file), $meta);
    }

    /**
     * {@inheritdoc}
     */
    public function createItemFromContents(RepositoryInterface $repository, $id, $contents, array $meta = array())
    {
        try {
            $this->createFileFromContents($id, $contents, $meta);

        } catch (\Exception $e) {
            throw new StorageException(
                sprintf(
                    "Item '%s' could not be created. An error occurred while writing the file.", $id
                ), 0, $e
            );
        }

        $item = new Item($repository, $id, $meta);

        /** @todo Also set contents? Pro: No re-reading would be required. Contra: Memory usage */

        return $item;
    }

    public function createFile($id, $file, array $meta = array())
    {
        return $this->createFileFromContents($id, $this->getFileContents($file), $meta);
    }

    public function createFileFromContents($id, $contents, array $meta = array())
    {
        $file = $this->getFilesystem()->createFile($id);

        if (isset($meta[ItemInterface::NAME])) {
            $file->setName($meta[ItemInterface::NAME]); // Original name
        }

        if (isset($meta[ItemInterface::SIZE])) {
            $file->setSize($meta[ItemInterface::SIZE]);
        }

        $gaufretteMeta = array();

        if (isset($meta[ItemInterface::TYPE])) {
            /** @todo This is AwsS3 specific; check Filesystem adapter?... */
            $gaufretteMeta['ContentType'] = $meta[ItemInterface::TYPE];
        }

        $file->setContent($contents, $gaufretteMeta);

        return $file;
    }

//    protected function getItemFromFile(File $file)
//    {
//    }

    protected function getFileContents($file)
    {
        /** @todo Error handling */
        return file_get_contents($file);
    }
}
