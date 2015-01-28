<?php

namespace Detail\File\Upload\Filter;

use Zend\Filter\File\RenameUpload as RenameUploadFilter;
use Zend\Filter\Exception as FilterException;

abstract class AbstractRenameFileFilter extends RenameUploadFilter implements
    RenameFileFilterInterface
{
    /**
     * {@inheritdoc}
     */
    public function __construct(array $options = array())
    {
        // Change default options (e.g. test.png will become test_4b3403665fea6.png
        $options = array_merge(
            array(
                'use_upload_name' => true,
                'randomize'       => true,
            ),
            $options
        );

        parent::__construct($options);
    }

    /**
     * {@inheritdoc}
     */
    public function filter($value)
    {
        if (!is_scalar($value) && !is_array($value)) {
            return $value;
        }

        // An uploaded file? Retrieve the 'tmp_name'
        if (is_array($value)) {
            if (!isset($value['tmp_name'])) {
                return $value;
            }

            $uploadData = $value;
            $sourceFile = $value['tmp_name'];
        } else {
            $uploadData = array(
                'tmp_name' => $value,
                'name'     => $value,
            );
            $sourceFile = $value;
        }

        if (isset($this->alreadyFiltered[$sourceFile])) {
            return $this->alreadyFiltered[$sourceFile];
        }

        $targetFile = $this->getFinalTarget($uploadData);
        $file = $this->createFile($sourceFile, $targetFile, $uploadData);

        $this->alreadyFiltered[$sourceFile] = $file;

        return $file;
    }

    /**
     * @param string $sourceFile Path to source file
     * @param string $targetFile Path to target file
     * @param array $uploadData
     * @return mixed
     */
    abstract protected function createFile($sourceFile, $targetFile, array $uploadData);

    /**
     * @param  array $uploadData $_FILES array
     * @return string
     */
    protected function getFinalTarget($uploadData)
    {
        $source = $uploadData['tmp_name'];
        $target = $this->getTarget();

        if (!isset($target) || $target == '*') {
            $target = basename($source);
        }

        // Get the target directory
        $info      = pathinfo($target);
        $targetDir = $info['dirname'] !== '.' ? ($info['dirname'] . DIRECTORY_SEPARATOR) : '';

        // Get the target filename
        if ($this->getUseUploadName()) {
            $targetFile = basename($uploadData['name']);
        } else {
            $targetFile = basename($target);

            if ($this->getUseUploadExtension() && !$this->getRandomize()) {
                $targetInfo = pathinfo($targetFile);
                $sourceInfo = pathinfo($uploadData['name']);

                if (isset($sourceInfo['extension'])) {
                    $targetFile = $targetInfo['filename'] . '.' . $sourceInfo['extension'];
                }
            }
        }

        if ($this->getRandomize()) {
            $targetFile = $this->applyRandomToFilename($uploadData['name'], $targetFile);
        }

        return $targetDir . $targetFile;
    }
}
