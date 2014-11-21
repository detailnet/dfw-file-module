<?php

namespace Detail\File\Item;

use Detail\File\Repository\RepositoryInterface;

class Item implements
    ItemInterface
{
    /**
     * @var RepositoryInterface
     */
    protected $repository;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var array
     */
    protected $meta;

    /**
     * {@inheritdoc}
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getMeta($forceReload = false)
    {
        if ($this->meta === null || $forceReload === true) {
            $this->meta = $this->getRepository()->getItemMeta($this->getId());
        }

        return $this->meta;
    }

    /**
     * @param RepositoryInterface $repository
     * @param $id
     * @param array $meta
     */
    public function __construct(RepositoryInterface $repository, $id, array $meta = null)
    {
        $this->repository = $repository;
        $this->id = $id; /** @todo Assert string */
        $this->meta = $meta;
    }

    /**
     * {@inheritdoc}
     */
    public function getContents()
    {
        /** @todo Cache? */
        return $this->getRepository()->getItemContents($this->getId());
    }

    public function getName()
    {
        $name = $this->getMetaByKey(self::NAME);

        if ($name === null) {
            $name = $this->getRepository()->getItemName($this->getId());
        }

        return $name;
    }

    public function getType()
    {
        $type = $this->getMetaByKey(self::TYPE);

        if ($type === null) {
            $type = $this->getRepository()->getItemType($this->getId());
        }

        return $type;
    }

    public function getSize()
    {
        $size = $this->getMetaByKey(self::SIZE);

        if ($size === null) {
            $size = $this->getRepository()->getItemSize($this->getId());
        }

        return $size;
    }

    public function getPublicUrl()
    {
        $url = $this->getMetaByKey(self::URL);

        if ($url === null) {
            $url = $this->getRepository()->getItemPublicUrl($this->getId());
        }

        return $url;
    }

    protected function getMetaByKey($key, $default = null, $forceReload = false)
    {
        $meta = $this->getMeta($forceReload);

        if (array_key_exists($key, $meta)) {
            return $meta[$key];
        }

        return $default;
    }
}
