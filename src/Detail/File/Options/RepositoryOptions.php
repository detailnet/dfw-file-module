<?php

namespace Detail\File\Options;

use Detail\Core\Options\AbstractOptions;

class RepositoryOptions extends AbstractOptions
{
    protected $storage;

    protected $derivatives = array();

    protected $keepRevisions = 0;
}
