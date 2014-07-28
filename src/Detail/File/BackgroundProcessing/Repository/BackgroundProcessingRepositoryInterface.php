<?php

namespace Detail\File\BackgroundProcessing\Repository;

use Detail\File\Repository\RepositoryInterface;

interface BackgroundProcessingRepositoryInterface extends RepositoryInterface
{
    /**
     * Create an item in the repository using a background task.
     *
     * This is basically the same as {@link createItem()}, but schedules the item
     * to be created asynchronously in the background.
     *
     * @param string $id Item ID
     * @param string $url Public URL to source file
     * @param array $meta Meta data
     * @param bool $createDerivatives Create derivatives?
     * @param array $callbackData Data to provide in complete message
     * @return void
     */
    public function createItemInBackground(
        $id, $url, array $meta = array(), $createDerivatives = true, array $callbackData = array()
    );
}
