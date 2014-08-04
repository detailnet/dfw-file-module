<?php

namespace Detail\File\Repository;

use Detail\File\BackgroundProcessing\Driver\DriverAwareTrait;
use Detail\File\BackgroundProcessing\Driver\DriverInterface;
use Detail\File\BackgroundProcessing\Repository\RepositoryInterface as BackgroundProcessingRepositoryInterface;
use Detail\File\Exception\ItemNotFoundException;
use Detail\File\Exception\RuntimeException;
use Detail\File\Item\ItemInterface;
use Detail\File\Resolver\ResolverAwareInterface;
use Detail\File\Resolver\ResolverAwareTrait;
use Detail\File\Resolver\ResolverInterface;
use Detail\File\Storage\StorageInterface;

class Repository implements
    BackgroundProcessingRepositoryInterface,
    ResolverAwareInterface
{
    use DriverAwareTrait;
    use ResolverAwareTrait;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var StorageInterface
     */
    protected $storage;

    /**
     * @param $name
     * @param StorageInterface $storage
     */
    public function __construct($name, StorageInterface $storage)
    {
        $this->name = $name;
        $this->storage = $storage;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function hasItem($id, $revision = null)
    {
        return $this->getStorage()->hasItem($id, $revision);
    }

    /**
     * {@inheritdoc}
     */
    public function getItem($id, $revision = null)
    {
        $item = $this->getStorage()->getItem($this, $id, $revision);

        if ($item === null) {
            throw new ItemNotFoundException(
                sprintf('Item "%s" does not exist in the repository', $id)
            );
        }

        return $item;
    }

    /**
     * {@inheritdoc}
     */
    public function getItemContents($id, $revision = null)
    {
        return $this->getStorage()->getItemContents($id, $revision);
    }

    /**
     * {@inheritdoc}
     */
    public function getItemMeta($id, $revision = null)
    {
        return $this->getStorage()->getItemMeta($id, $revision);
    }

    /**
     * {@inheritdoc}
     */
    public function getItemName($id, $revision = null)
    {
        return $this->getStorage()->getItemName($id, $revision);
    }

    /**
     * {@inheritdoc}
     */
    public function getItemType($id, $revision = null)
    {
        return $this->getStorage()->getItemType($id, $revision);
    }

    /**
     * {@inheritdoc}
     */
    public function getItemSize($id, $revision = null)
    {
        return $this->getStorage()->getItemSize($id, $revision);
    }

    /**
     * {@inheritdoc}
     */
    public function getItemPublicUrl($id, $revision = null)
    {
        /** @todo Add support for revision */
        $url = $this->getPublicUrlResolver()->resolve($id);

        return $url;

//        return $this->getStorage()->getItemPublicUrl($id, $revision);
    }

    /**
     * {@inheritdoc}
     */
    public function createItem($id, $file, array $meta = array(), $createDerivatives = true)
    {
        return $this->getStorage()->createItem($this, $id, $file, $meta);
    }

    /**
     * {@inheritdoc}
     */
    public function createItemFromContents($id, $contents, array $meta = array(), $createDerivatives = true)
    {
        return $this->getStorage()->createItemFromContents($this, $id, $contents, $meta);
    }

    /**
     * {@inheritdoc}
     */
    public function createItemInBackground(
        $id, $url, array $meta = array(), $createDerivatives = true, array $callbackData = array()
    ) {
        $driver = $this->getBackgroundDriver();
        $message = $driver->getMessageFactory()->createNew(
            $this->getName(), $id, $url, $meta, $createDerivatives, $callbackData
        );

        $driver->createItem($message);
    }

    /**
     * {@inheritdoc}
     */
    public function reportItemCreatedInBackground(ItemInterface $item, $createDerivatives, array $callbackData)
    {
        $driver = $this->getBackgroundDriver();
        $message = $driver->getMessageFactory()->createNew(
            $this->getName(), $item->getId(), $item->getPublicUrl(), $item->getMeta(), $createDerivatives, $callbackData
        );

        $driver->completeItem($message);
    }

    /**
     * {@inheritdoc}
     */
    public function deleteItem($id)
    {
        // TODO: Implement deleteItem() method.
    }

    /**
     * {@inheritdoc}
     */
    public function destroyItem($id)
    {
        // TODO: Implement destroyItem() method.
    }

    /**
     * {@inheritdoc}
     */
    public function copyItem($id, $targetId, $withRevisions = true)
    {
        // TODO: Implement copyItem() method.
    }

    /**
     * {@inheritdoc}
     */
    public function refreshItem($id, array $meta = array(), $createDerivatives = true, $force = true)
    {
        // TODO: Implement refreshItem() method.
    }

    /**
     * @return StorageInterface
     */
    protected function getStorage()
    {
        return $this->storage;
    }

    /**
     * @return DriverInterface
     * @throws RuntimeException
     */
    protected function getBackgroundDriver()
    {
        if ($this->backgroundDriver === null) {
            throw new RuntimeException(
                'Background processing is not enabled for this repository; no driver was provided'
            );
        }

        return $this->backgroundDriver;
    }

    /**
     * @return ResolverInterface
     * @throws RuntimeException
     */
    protected function getPublicUrlResolver()
    {
        if ($this->publicUrlResolver === null) {
            throw new RuntimeException(
                'Public URL resolving is not enabled for this repository; no resolver was provided'
            );
        }

        return $this->publicUrlResolver;
    }
}
