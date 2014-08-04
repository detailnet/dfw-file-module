<?php

namespace Detail\File\Resolver;

interface ResolverAwareInterface
{
    /**
     * @param ResolverInterface $resolver
     */
    public function setPublicUrlResolver(ResolverInterface $resolver);
} 
