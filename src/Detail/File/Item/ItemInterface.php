<?php

namespace Detail\File\Item;

interface ItemInterface
{
//    /**
//     * @return \SplFileInfo
//     */
//    public function getFile();

    public function getKey();

    public function getContents();

    public function getMetadata();

//    public function getPublicUrl();
}
