<?php

namespace Detail\File\Item;

use Detail\File\Repository\RepositoryInterface;

interface ItemInterface
{
    const NAME = 'name';
    const TYPE = 'type';
    const SIZE = 'size';

//    /**
//     * @return \SplFileInfo
//     */
//    public function getFile();

    /**
     * @return RepositoryInterface
     */
    public function getRepository();

    /**
     * @return string
     */
    public function getId();

    /**
     * @return string
     */
    public function getContents();

    /**
     * @param bool $forceReload
     * @return array
     */
    public function getMeta($forceReload = false);

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getType();

    /**
     * @return int
     */
    public function getSize();

//    public function getPublicUrl();
}
