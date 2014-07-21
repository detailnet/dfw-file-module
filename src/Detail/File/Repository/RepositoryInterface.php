<?php

namespace Detail\File\Repository;

use Detail\File\Item\ItemInterface;

interface RepositoryInterface
{
    /**
     * Get item.
     *
     * @param string|Id $id Item ID
     * @param string|Revision $revision Revision
     * @return ItemInterface
     */
    public function getItem($id, $revision = null);

    /**
     * Create item.
     *
     * A new (first) revision will be created.
     *
     * @param string|Id $id Item ID
     * @param \SplFileInfo|string Path to source file
     * @param array $meta Meta data
     * @param bool $createDerivatives Create derivatives?
     * @return ItemInterface Created item
     */
    public function createItem(
        $id, $file, array $meta = array(), $createDerivatives = true
    );

    /**
     * Create item from contents.
     *
     * A new (first) revision will be created.
     *
     * @param string|Id $id Item ID
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
     * @param string|Id $id Item ID
     * @return void
     */
    public function deleteItem($id);

    /**
     * Destroy item.
     *
     * All revision are destroyed.
     *
     * @param string|Id $id Item ID
     * @return void
     */
    public function destroyItem($id);

    /**
     * Copy item.
     *
     * Create an exact copy of the item under a different ID.
     * Optionally, all but the latest revision can be skipped.
     *
     * @param string|Id $id Item ID
     * @param string|Id $targetId Target item ID
     * @param bool $withRevisions Wether or not to copy the revisions
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
     * @param string|Id $id Item ID
     * @param array $meta Meta data
     * @param bool|array $createDerivatives Create derivatives?
     * @param bool $force Force refresh (otherwise derivatives are skipped when already existing)
     * @return ItemInterface Refreshed item
     */
    public function refreshItem($id, array $meta = array(), $createDerivatives = true, $force = true);
}
