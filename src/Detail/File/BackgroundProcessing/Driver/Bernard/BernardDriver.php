<?php

namespace Detail\File\BackgroundProcessing\Driver\Bernard;

use Detail\Bernard\Message\Messenger;
use Detail\File\BackgroundProcessing\Driver\DriverInterface;
use Detail\File\BackgroundProcessing\Message\MessageInterface;
use Detail\File\Exception\RuntimeException;

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
    protected $createQueueName = 'create-item';

    /**
     * @var string
     */
    protected $completeQueueName = 'complete-item';

    /**
     * @var array
     */
    protected $queueOptions = array();

    /**
     * @return Messenger
     */
    public function getMessenger()
    {
        return $this->messenger;
    }

    /**
     * @param Messenger $messenger
     */
    public function setMessenger(Messenger $messenger)
    {
        $this->messenger = $messenger;
    }

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

    /**
     * @param string $queueName
     * @throws RuntimeException
     * @return array
     */
    public function getQueueOptions($queueName = null)
    {
        $options = $this->queueOptions;

        if ($queueName !== null) {
            $options = array_key_exists($queueName, $options) ? $options[$queueName] : array();

            if (!is_array($options)) {
                throw new RuntimeException(
                    sprintf(
                        'Invalid options for queue "%s" encountered; expected "array", received "%s"',
                        $queueName, (is_object($options) ? get_class($options) : gettype($options))
                    )
                );
            }
        }

        return $options;
    }

    /**
     * @param array $queueOptions
     */
    public function setQueueOptions(array $queueOptions)
    {
        $this->queueOptions = $queueOptions;
    }

    public function __construct(
        Messenger $messenger, $createQueueName = null, $completeQueueName = null, array $queueOptions = null
    ) {
        $this->setMessenger($messenger);

        if ($createQueueName !== null) {
            $this->setCreateQueueName($createQueueName);
        }

        if ($completeQueueName !== null) {
            $this->setCompleteQueueName($completeQueueName);
        }

        if ($queueOptions !== null) {
            $this->setQueueOptions($queueOptions);
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
        $messenger->produce($bernardMessage, $this->getQueueOptions($queue));
    }
}
