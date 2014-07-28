<?php

namespace Detail\File\BackgroundProcessing\Message;

interface MessageFactoryInterface
{
    /**
     * @param $repository
     * @param $id
     * @param $url
     * @param array $meta
     * @param bool $createDerivatives
     * @param array $callbackData
     * @param string $messageClass
     * @return MessageInterface
     */
    public function createNew(
        $repository, $id, $url, array $meta = array(), $createDerivatives = true,
        array $callbackData = array(), $messageClass = null
    );
}
