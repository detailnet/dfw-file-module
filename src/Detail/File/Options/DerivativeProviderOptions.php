<?php

namespace Detail\File\Options;

use Detail\Core\Options\AbstractOptions;

class DerivativeProviderOptions extends AbstractOptions
{
    /**
     * @var string
     */
    protected $provider;

    /**
     * @var bool
     */
    protected $autoCreate = false;
}
