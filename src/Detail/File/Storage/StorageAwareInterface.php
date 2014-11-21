<?php

namespace Detail\File\Storage;

interface StorageAwareInterface
{
    /**
     * @param StorageInterface $storage
     */
    public function setStorage(StorageInterface $storage);
} 
