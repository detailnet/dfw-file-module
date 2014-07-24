<?php

namespace Detail\File\Upload\Filter;

use Detail\File\Repository\RepositoryInterface;

use Zend\Filter\Exception as FilterException;

class RepositoryTargetedFileFilter extends AbstractRenameFileFilter
{
    /**
     * @var RepositoryInterface
     */
    protected $repository;

    /**
     * @return RepositoryInterface
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param RepositoryInterface $repository
     */
    public function setRepository($repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param RepositoryInterface $repository
     * @param array $options
     */
    public function __construct(RepositoryInterface $repository, array $options = array())
    {
        parent::__construct($options);

        $this->setRepository($repository);
    }

    /**
     * {@inheritdoc}
     */
    protected function createFile($sourceFile, $targetFile, array $uploadData)
    {
        $repository = $this->getRepository();

        if ($repository->hasItem($targetFile) && !$this->getOverwrite()) {
            throw new FilterException\InvalidArgumentException(
                sprintf("File '%s' could not be saved. It already exists.", $targetFile)
            );
        }

        try {
            $item = $repository->createItem($targetFile, $sourceFile, $uploadData);

        } catch (\Exception $e) {
            throw new FilterException\RuntimeException(
                sprintf(
                    "File '%s' could not be saved. An error occurred while processing the file.",
                    $targetFile
                ), 0, $e
            );
        }

        return $item;
    }
}
