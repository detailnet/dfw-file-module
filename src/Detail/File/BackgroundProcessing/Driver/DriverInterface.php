<?php

namespace Detail\File\BackgroundProcessing\Driver;

use Detail\File\BackgroundProcessing\Message\MessageFactoryInterface;
use Detail\File\BackgroundProcessing\Message\MessageInterface;

interface DriverInterface
{
    /**
     * @return MessageFactoryInterface
     */
    public function getMessageFactory();

    /**
     * @param MessageInterface $message
     * @return void
     */
    public function createItem(MessageInterface $message);

    /**
     * @param MessageInterface $message
     * @return void
     */
    public function completeItem(MessageInterface $message);
}
