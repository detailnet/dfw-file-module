<?php

namespace Detail\File\Options\BackgroundProcessing\Driver;

use Detail\Core\Options\AbstractOptions;

class BernardDriverOptions extends AbstractOptions
{
    /**
     * @var string
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
     * @param string $createQueueName
     */
    public function setCreateQueueName($createQueueName)
    {
        $this->createQueueName = $createQueueName;
    }

    /**
     * @return string
     */
    public function getCreateQueueName()
    {
        return $this->createQueueName;
    }

    /**
     * @param string $completeQueueName
     */
    public function setCompleteQueueName($completeQueueName)
    {
        $this->completeQueueName = $completeQueueName;
    }

    /**
     * @return string
     */
    public function getCompleteQueueName()
    {
        return $this->completeQueueName;
    }
}
