<?php

namespace Detail\File\Item;

interface ItemInterface
{
    /**
     * @return \SplFileInfo
     */
    public function getFile();
}
