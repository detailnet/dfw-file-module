<?php

namespace Detail\File\Options\Resolver;

use Detail\Core\Options\AbstractOptions;

class PrefixResolverOptions extends AbstractOptions
{
    /**
     * @var string
     */
    protected $prefix = '';

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @param string $prefix
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }
}
