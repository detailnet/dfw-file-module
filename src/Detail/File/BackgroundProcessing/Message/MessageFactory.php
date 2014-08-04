<?php

namespace Detail\File\BackgroundProcessing\Message;

use Detail\Bernard\Message\MessageFactoryInterface as BernardMessageFactoryInterface;
use Detail\File\Exception\RuntimeException;

class MessageFactory implements
    MessageFactoryInterface,
    BernardMessageFactoryInterface
{
    const KEY_REPOSITORY = 'repository';
    const KEY_ID = 'id';
    const KEY_PUBLIC_URL = 'public_url';
    const KEY_META = 'meta';
    const KEY_CREATE_DERIVATIVES = 'create_derivatives';
    const KEY_CALLBACK_DATA = 'callback_data';

    /**
     * Default message class.
     *
     * @var string
     */
    protected $messageClass = 'Detail\File\BackgroundProcessing\Message\Message';

    /**
     * @param string $class
     * @return string
     */
    public function getMessageClass($class = null)
    {
        if (is_string($class)) {
            return $class;
        }

        return $this->messageClass;
    }

    /**
     * @param string $messageClass
     */
    public function setMessageClass($messageClass)
    {
        $this->messageClass = $messageClass;
    }

    /**
     * {@inheritdoc}
     */
    public function accepts($message)
    {
        return $this->assertMessageType($message, false);
    }

    /**
     * {@inheritdoc}
     */
    public function createNew(
        $repository, $id, $url, array $meta = array(), $createDerivatives = true,
        array $callbackData = array(), $messageClass = null
    ) {
        /** @todo Assert arguments */
        /** @todo Check if class exists */
        $messageClass = $this->getMessageClass($messageClass);
        $message = new $messageClass($repository, $id, $url, $meta, $createDerivatives, $callbackData);

        $this->assertMessageType($message);

        return $message;
    }

    /**
     * {@inheritdoc}
     */
    public function createFromArray(array $messageData, $messageClass = null)
    {
        $requiredKeys = array(
            self::KEY_REPOSITORY,
            self::KEY_ID,
            self::KEY_PUBLIC_URL,
        );

        foreach ($requiredKeys as $key) {
            if (!isset($messageData[$key]) || strlen($messageData[$key]) === 0) {
                throw new RuntimeException(
                    sprintf('Invalid or no value for message key "%s"', $key)
                );
            }
        }

        return $this->createNew(
            $messageData[self::KEY_REPOSITORY],
            $messageData[self::KEY_ID],
            $messageData[self::KEY_PUBLIC_URL],
            isset($messageData[self::KEY_META]) ? $messageData[self::KEY_META] : array(),
            isset($messageData[self::KEY_CREATE_DERIVATIVES]) ? $messageData[self::KEY_CREATE_DERIVATIVES] : true,
            isset($messageData[self::KEY_CALLBACK_DATA]) ? $messageData[self::KEY_CALLBACK_DATA] : array(),
            $messageClass
        );
    }

    /**
     * {@inheritdoc}
     */
    public function toArray($message)
    {
        $this->assertMessageType($message);

        return array(
            self::KEY_REPOSITORY         => $message->getRepository(),
            self::KEY_ID                 => $message->getId(),
            self::KEY_PUBLIC_URL         => $message->getPublicUrl(),
            self::KEY_META               => $message->getMeta(),
            self::KEY_CREATE_DERIVATIVES => $message->getCreateDerivatives(),
            self::KEY_CALLBACK_DATA      => $message->getCallbackData(),
        );
    }

    /**
     * @param MessageInterface $message
     * @param bool $failOnMismatch
     * @return bool
     * @throws RuntimeException
     */
    protected function assertMessageType($message, $failOnMismatch = true)
    {
        if (!$message instanceof MessageInterface) {
            if ($failOnMismatch !== false) {
                throw new RuntimeException(
                    sprintf(
                        '%s only accepts message objects of type' .
                        'Detail\Bernard\Message\MessageInterface; received "%s"',
                        get_class($this),
                        (is_object($message) ? get_class($message) : gettype($message))
                    )
                );
            } else {
                return false;
            }
        }

        return true;
    }
}
