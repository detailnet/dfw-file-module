<?php

namespace Detail\File\BackgroundProcessing\Bernard\Receiver;

use Detail\Bernard\Message\Messenger;
use Detail\Bernard\Receiver\AbstractReceiver;
use Detail\File\BackgroundProcessing\Repository\RepositoryInterface as BackgroundProcessingRepositoryInterface;
use Detail\File\Exception\RuntimeException;
use Detail\File\Repository\RepositoryCollectionInterface;

use Bernard\Message as BernardMessage;

use Psr\Log\LogLevel;

class CreateItemReceiver extends AbstractReceiver
{
    /**
     * @var RepositoryCollectionInterface
     */
    protected $repositories;

    /**
     * @var Messenger
     */
    protected $messenger;

    public function __construct(RepositoryCollectionInterface $repositories, Messenger $messenger)
    {
        $this->repositories = $repositories;
        $this->messenger = $messenger;
    }

    public function createItem(BernardMessage $message)
    {
        try {
            $itemMessage = $this->getMessenger()->decodeMessage($message);
            $repository = $this->getRepository($itemMessage->getRepository());

            if (!$repository instanceof BackgroundProcessingRepositoryInterface) {
                throw new RuntimeException(
                    sprintf(
                        '%s only accepts repository objects of type' .
                        'Detail\File\BackgroundProcessing\Repository\BackgroundProcessingRepositoryInterface; received "%s"',
                        get_class($this),
                        (is_object($repository) ? get_class($repository) : gettype($repository))
                    )
                );
            }

            $item = $repository->createItem(
                $itemMessage->getId(), $itemMessage->getPublicUrl(),
                $itemMessage->getMeta(), $itemMessage->getDerivatives()
            );

            $repository->reportItemCreatedInBackground($item, $itemMessage->callbackData());
        } catch (\Exception $e) {
            /** @todo Handle known exception for better readability... */
            $this->log($e, LogLevel::CRITICAL);
            throw $e; // So that the message in not de-queued.
        }
    }

    /**
     * @param string $name
     * @return BackgroundProcessingRepositoryInterface
     */
    protected function getRepository($name)
    {
        return $this->getRepositories()->get($name);
    }

    /**
     * @return RepositoryCollectionInterface
     */
    protected function getRepositories()
    {
        return $this->repositories;
    }

    /**
     * @return Messenger
     */
    protected function getMessenger()
    {
        return $this->messenger;
    }
}
