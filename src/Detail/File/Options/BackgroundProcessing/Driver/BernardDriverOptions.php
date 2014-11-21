<?php

namespace Detail\File\Options\BackgroundProcessing\Driver;

use Detail\Bernard\Options\QueueOptions;
use Detail\Core\Options\AbstractOptions;

class BernardDriverOptions extends AbstractOptions
{
    /**
     * @var string
     */
    protected $messenger;

    /**
     * @var QueueOptions
     */
    protected $createQueue;

    /**
     * @var QueueOptions
     */
    protected $completeQueue;

    /**
     * @return string
     */
    public function getMessenger()
    {
        return $this->messenger;
    }

    /**
     * @param string $messenger
     */
    public function setMessenger($messenger)
    {
        $this->messenger = $messenger;
    }

    /**
     * @param array $createQueue
     */
    public function setCreateQueue(array $createQueue)
    {
        $this->createQueue = new QueueOptions($createQueue);
    }

    /**
     * @return QueueOptions
     */
    public function getCreateQueue()
    {
        if (!$this->createQueue instanceof QueueOptions) {
            $this->createQueue = new QueueOptions(array('name' => 'create-item'));
        }

        return $this->createQueue;
    }

    /**
     * @param array $completeQueue
     */
    public function setCompleteQueue(array $completeQueue)
    {
        $this->completeQueue = new QueueOptions($completeQueue);
    }

    /**
     * @return QueueOptions
     */
    public function getCompleteQueue()
    {
        if (!$this->completeQueue instanceof QueueOptions) {
            $this->completeQueue = new QueueOptions(array('name' => 'complete-item'));
        }

        return $this->completeQueue;
    }
}
