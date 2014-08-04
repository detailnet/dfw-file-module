<?php

namespace Detail\File\Resolver;

use Detail\File\Exception\InvalidArgumentException;

class PrefixResolver implements
    ResolverInterface
{
    /**
     * @var string
     */
    protected $prefix = '';

    /**
     * @var bool
     */
    protected $encodeUrl = true;

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @param string $prefix
     * @throws InvalidArgumentException
     */
    public function setPrefix($prefix)
    {
        if (!is_string($prefix)) {
            /** @var mixed $prefix */
            throw new InvalidArgumentException(
                sprintf(
                    'Expected string as prefix for the resolver; received "%s"',
                    (is_object($prefix) ? get_class($prefix) : gettype($prefix))
                )
            );
        }

        $this->prefix = $prefix;
    }

    /**
     * @return boolean
     */
    public function getEncodeUrl()
    {
        return $this->encodeUrl;
    }

    /**
     * @param boolean $encodeUrl
     */
    public function setEncodeUrl($encodeUrl)
    {
        $this->encodeUrl = $encodeUrl;
    }

    /**
     * @param string $prefix
     * @param bool $encodeUrl
     */
    public function __construct($prefix = null, $encodeUrl = null)
    {
        if ($prefix !== null) {
            $this->setPrefix($prefix);
        }

        if ($encodeUrl !== null) {
            $this->setEncodeUrl($encodeUrl);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function resolve($id)
    {
        return $this->getPrefix() . ($this->getEncodeUrl() === false ? $id : urlencode($id));
    }
}
