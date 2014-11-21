<?php

namespace Detail\File\BackgroundProcessing\Message;

interface MessageInterface
{
    /**
     * @param $repository
     * @param $id
     * @param $url
     * @param array $meta
     * @param bool $createDerivatives
     * @param array $callbackData
     */
    public function __construct(
        $repository, $id, $url, array $meta = array(), $createDerivatives = true,
        array $callbackData = array()
    );

    /**
     * @return string
     */
    public function getId();

    /**
     * @return string
     */
    public function getRepository();

    /**
     * @return string
     */
    public function getPublicUrl();

    /**
     * @return array
     */
    public function getMeta();

    /**
     * @return bool|array
     */
    public function getCreateDerivatives();

    /**
     * @return array
     */
    public function getCallbackData();
} 
