<?php

namespace Detail\File\Resolver;

trait ResolverAwareTrait
{
    /**
     * @var ResolverInterface
     */
    protected $publicUrlResolver;

    /**
     * @param ResolverInterface $resolver
     */
    public function setPublicUrlResolver(ResolverInterface $resolver)
    {
        $this->publicUrlResolver = $resolver;
    }
}
