<?php

namespace Detail\File\Resolver;

interface ResolverInterface
{
    /**
     * Resolve URL.
     *
     * @param string $id Item ID
     * @return string
     */
    public function resolve($id);
} 
