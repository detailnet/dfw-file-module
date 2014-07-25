<?php

namespace Detail\File\Repository;

use Detail\File\Item\ItemInterface;
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

    /**
     * {@inheritdoc}
     */
    public function hasItem($id, $revision = null)
    {
        // TODO: Implement hasItem() method.
    }

    /**
     * {@inheritdoc}
     */
    public function getItem($id, $revision = null)
    {
        // TODO: Implement getItem() method.
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
}
