<?php

namespace Detail\File\Repository;

use Detail\File\Item\ItemInterface;

interface RepositoryInterface
{
    /**
     * Check if an item exists in the repository.
     *
     * @param string $id Item ID
     * @param string $revision Revision
     * @return ItemInterface
     */
    public function hasItem($id, $revision = null);

    /**
     * Retrieve an item from the repository.
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
     * @return string
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
     * Create an item in the repository.
     *
     * A new (first) revision will be created.
     *
     * @param string $id Item ID
     * @param \SplFileInfo|string Path to source file
     * @param array $meta Meta data
     * @param bool $createDerivatives Create derivatives?
     * @return ItemInterface Created item
     */
    public function createItem(
        $id, $file, array $meta = array(), $createDerivatives = true
    );

    /**
     * Create an item in the repository directly from file contents.
     *
     * A new (first) revision will be created.
     *
     * @param string $id Item ID
     * @param string $contents Source file contents
     * @param array $meta Meta data
     * @param bool $createDerivatives Create derivatives?
     * @return ItemInterface Created item
     */
    public function createItemFromContents(
        $id, $contents, array $meta = array(), $createDerivatives = true
    );

    /**
     * Delete item.
     *
     * All revisions are kept, but the most current file is made unaccessible.
     *
     * @param string $id Item ID
     * @return void
     */
    public function deleteItem($id);

    /**
     * Destroy item.
     *
     * All revision are destroyed.
     *
     * @param string $id Item ID
     * @return void
     */
    public function destroyItem($id);

    /**
     * Copy item.
     *
     * Create an exact copy of the item under a different ID.
     * Optionally, all but the latest revision can be skipped.
     *
     * @param string $id Item ID
     * @param string $targetId Target item ID
     * @param bool $withRevisions Whether or not to copy the revisions
     * @return ItemInterface Copy of the item
     */
    public function copyItem($id, $targetId, $withRevisions = true);

    /**
     * Refresh item.
     *
     * Metadata and derivatives are recreated, replacing what was before.
     * No new revision is created.
     * By default, derivatives with type "manual" are recreated, too.
     *
     * @param string $id Item ID
     * @param array $meta Meta data
     * @param bool|array $createDerivatives Create derivatives?
     * @param bool $force Force refresh (otherwise derivatives are skipped when already existing)
     * @return ItemInterface Refreshed item
     */
    public function refreshItem($id, array $meta = array(), $createDerivatives = true, $force = true);
}
