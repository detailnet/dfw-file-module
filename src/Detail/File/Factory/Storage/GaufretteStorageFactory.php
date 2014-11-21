<?php

namespace Detail\File\Factory\Storage;

use Zend\ServiceManager\ServiceLocatorInterface;

use Detail\File\Storage\GaufretteStorage as Storage;
use Detail\File\Options\Storage\GaufretteStorageOptions as Options;

class GaufretteStorageFactory implements
    StorageFactoryInterface
{
    /**
     * @var \Detail\Gaufrette\Service\FilesystemService
     */
    protected $filesystemService;

    /**
     * {@inheritdoc}
     */
    public function createStorage(ServiceLocatorInterface $serviceLocator, array $config)
    {
        $options = new Options($config);
        $filesystem = $this->getFilesystemService($serviceLocator)->get($options->getFilesystem());
        $storage = new Storage($filesystem);

        return $storage;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return \Detail\Gaufrette\Service\FilesystemService
     */
    protected function getFilesystemService(ServiceLocatorInterface $serviceLocator)
    {
        if ($this->filesystemService === null) {
            /** @todo Use factory for this "factory" and inject filesystem service */
            $this->filesystemService = $serviceLocator->get('Detail\Gaufrette\Service\FilesystemService');
        }

        return $this->filesystemService;
    }
}
