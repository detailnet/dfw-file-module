<?php

namespace Detail\File\BackgroundProcessing\Driver\Bernard;

use Detail\Bernard\Message\Messenger;
use Detail\File\BackgroundProcessing\Driver\DriverInterface;
use Detail\File\BackgroundProcessing\Message\MessageInterface;

class BernardDriver
    implements DriverInterface
{
    /**
     * @var Messenger
     */
    protected $messenger;

    /**
     * @var string
     */
    protected $createQueueName = 'file-create';

    /**
     * @var string
     */
    protected $completeQueueName = 'file-complete';

    /**
     * @return string
     */
    public function getCreateQueueName()
    {
        return $this->createQueueName;
    }

    /**
     * @param string $createQueueName
     */
    public function setCreateQueueName($createQueueName)
    {
        $this->createQueueName = $createQueueName;
    }

    /**
     * @return string
     */
    public function getCompleteQueueName()
    {
        return $this->completeQueueName;
    }

    /**
     * @param string $completeQueueName
     */
    public function setCompleteQueueName($completeQueueName)
    {
        $this->completeQueueName = $completeQueueName;
    }

    public function __construct(
        Messenger $messenger, $createQueueName = null, $completeQueueName = null
    ) {
        $this->messenger = $messenger;

        if ($createQueueName !== null) {
            $this->setCreateQueueName($createQueueName);
        }

        if ($completeQueueName !== null) {
            $this->setCompleteQueueName($completeQueueName);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getMessageFactory()
    {
        return $this->getMessenger()->getMessageFactory();
    }

    /**
     * {@inheritdoc}
     */
    public function createItem(MessageInterface $message)
    {
        $this->produceMessage($message, $this->getCreateQueueName());
    }

    /**
     * {@inheritdoc}
     */
    public function completeItem(MessageInterface $message)
    {
        $this->produceMessage($message, $this->getCompleteQueueName());
    }

    protected function produceMessage(MessageInterface $message, $queue)
    {
        $messenger = $this->getMessenger();
        $bernardMessage = $messenger->encodeMessage($message, $queue);
        $messenger->produce($bernardMessage);
    }

    protected function getMessenger()
    {
        return $this->messenger;
    }
}
