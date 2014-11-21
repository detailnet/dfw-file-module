<?php

namespace Detail\File\Resolver;

use Gaufrette\Adapter as GaufretteAdapter;

use Detail\File\Exception\RuntimeException;
use Detail\File\Storage\GaufretteStorage;
use Detail\File\Storage\StorageAwareInterface;
use Detail\File\Storage\StorageAwareTrait;

class StorageResolver implements
    ResolverInterface,
    StorageAwareInterface
{
    use StorageAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function resolve($id)
    {
        $storage = $this->getStorage();

        /** @todo Support other storages */
        if ($storage instanceof GaufretteStorage) {
            $id = $this->resolveFromGaufretteAdapter($id, $storage->getFilesystem()->getAdapter());
        } else {
            throw new RuntimeException(
                sprintf('Storage of type "%s" is not supported', get_class($storage))
            );
        }

        return $id;
    }

    protected function resolveFromGaufretteAdapter($id, GaufretteAdapter $adapter)
    {
        /** @todo Support other adapters */
        if ($adapter instanceof GaufretteAdapter\AwsS3) {
            $id = $adapter->getUrl($id);
        } else {
            throw new RuntimeException(
                sprintf('Gaufrette adapter of type "%s" is not supported', get_class($adapter))
            );
        }

        return $id;
    }

    protected function getStorage()
    {
        return $this->storage;
    }
}
