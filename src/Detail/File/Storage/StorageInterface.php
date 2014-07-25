<?php

namespace Detail\File\Storage;

use Detail\File\Item\ItemInterface;
use Detail\File\Repository\RepositoryInterface;

interface StorageInterface
{
    /**
     * Retrieve an item.
     *
     * @param string $id Item ID
     * @param string $revision Revision
     * @return ItemInterface
     */
    public function getItem($id, $revision = null);

    /**
     * Retrieve an item's file contents.
     *
     * @param string $id Item ID
     * @param string $revision Revision
     * @return array
     */
    public function getItemContents($id, $revision = null);

    /**
     * Retrieve an item's meta data.
     *
     * @param string $id Item ID
     * @param string $revision Revision
     * @return array
     */
    public function getItemMeta($id, $revision = null);

    /**
     * Retrieve an item's name.
     *
     * @param string $id Item ID
     * @param string $revision Revision
     * @return string
     */
    public function getItemName($id, $revision = null);

    /**
     * Retrieve an item's mime type.
     *
     * @param string $id Item ID
     * @param string $revision Revision
     * @return string
     */
    public function getItemType($id, $revision = null);

    /**
     * Retrieve an item's file size.
     *
     * @param string $id Item ID
     * @param string $revision Revision
     * @return int
     */
    public function getItemSize($id, $revision = null);

    /**
     * @param RepositoryInterface $repository
     * @param string $id Item ID
     * @param string $file
     * @param array $meta
     * @return ItemInterface
     */
    public function createItem(RepositoryInterface $repository, $id, $file, array $meta = array());

    /**
     * @param RepositoryInterface $repository
     * @param string $id Item ID
     * @param string $contents
     * @param array $meta
     * @return ItemInterface
     */
    public function createItemFromContents(
        RepositoryInterface $repository, $id, $contents, array $meta = array()
    );
}
