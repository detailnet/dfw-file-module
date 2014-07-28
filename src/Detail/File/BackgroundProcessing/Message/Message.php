<?php

namespace Detail\File\BackgroundProcessing\Message;

class Message implements
    MessageInterface
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $repository;

    /**
     * @var string
     */
    protected $publicUrl;

    /**
     * @var array
     */
    protected $meta = array();

    /**
     * @var bool
     */
    protected $createDerivatives = true;

    /**
     * @var array
     */
    protected $callbackData = array();

//    /**
//     * @param string $id
//     */
//    public function setId($id)
//    {
//        $this->id = $id;
//    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

//    /**
//     * @param string $repository
//     */
//    public function setRepository($repository)
//    {
//        $this->repository = $repository;
//    }

    /**
     * @return string
     */
    public function getRepository()
    {
        return $this->repository;
    }

//    /**
//     * @param string $publicUrl
//     */
//    public function setPublicUrl($publicUrl)
//    {
//        $this->publicUrl = $publicUrl;
//    }

    /**
     * @return string
     */
    public function getPublicUrl()
    {
        return $this->publicUrl;
    }

//    /**
//     * @param array $meta
//     */
//    public function setMeta(array $meta)
//    {
//        $this->meta = $meta;
//    }

    /**
     * @return array
     */
    public function getMeta()
    {
        return $this->meta;
    }

//    /**
//     * @param bool|array $createDerivatives
//     */
//    public function setCreateDerivatives($createDerivatives)
//    {
//        $this->createDerivatives = $createDerivatives;
//    }

    /**
     * @return bool|array
     */
    public function getCreateDerivatives()
    {
        return $this->createDerivatives;
    }

    /**
     * @return array
     */
    public function getCallbackData()
    {
        return $this->callbackData;
    }

//    /**
//     * @param array $callbackData
//     */
//    public function setCallbackData(array $callbackData)
//    {
//        $this->callbackData = $callbackData;
//    }

    public function __construct(
        $repository, $id, $url, array $meta = array(), $createDerivatives = true,
        array $callbackData = array()
    ) {
        /** @todo Assert arguments */

        $this->repository = $repository;
        $this->id = $id;
        $this->publicUrl = $url;
        $this->meta = $meta;
        $this->createDerivatives = $createDerivatives;
        $this->callbackData = $callbackData;
    }
}
