<?php

namespace Detail\File\Options;

use Detail\Core\Options\AbstractOptions;
use Detail\Core\Options\TypeAwareOptionsTrait;

class RepositoryOptions extends AbstractOptions
{
    use TypeAwareOptionsTrait;

    /**
     * @var boolean
     */
    protected $useProxy = false;

    /**
     * @return boolean
     */
    public function getUseProxy()
    {
        return $this->useProxy;
    }

    /**
     * @param boolean $useProxy
     */
    public function setUseProxy($useProxy)
    {
        $this->useProxy = $useProxy;
    }
}
