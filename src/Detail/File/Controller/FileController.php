<?php

namespace Detail\File\Controller;

use Zend\Mvc\Controller\AbstractActionController;
//use Zend\Mvc\MvcEvent;
use Zend\Http\Response as Response;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LogLevel;

use Detail\File\Exception\InvalidParamException;
use Detail\File\Exception\ItemNotFoundException;
use Detail\File\Repository\RepositoryCollectionAwareTrait;
use Detail\File\Repository\RepositoryCollectionInterface;
use Detail\Log\Service\LoggerAwareTrait;

class FileController extends AbstractActionController implements
    LoggerAwareInterface
{
    use RepositoryCollectionAwareTrait;
    use LoggerAwareTrait;

//    public function onDispatch(MvcEvent $e)
//    {
//        $this->setViewDisabled(true);
//
//        return parent::onDispatch($e);
//    }

    public function __construct(RepositoryCollectionInterface $repositories)
    {
        $this->setFileRepositoryCollection($repositories);
    }

    /**
     * Download file.
     *
     * @return Response
     */
    public function downloadAction()
    {
        return $this->sendFile(true);
    }

    /**
     * Show file.
     *
     * @return Response
     */
    public function showAction()
    {
        return $this->sendFile(false);
    }

    /**
     * Helper to send file.
     *
     * @param boolean $forceDownload Send file as download?
     * @throws InvalidParamException
     * @return Response
     */
    protected function sendFile($forceDownload = false)
    {
        /** @todo Check param for existence */
        $repositoryName = $this->params('repository');

        if (strlen($repositoryName) === 0) {
            throw new InvalidParamException('Invalid or no value for required parameter "repository"');
        }

        $repository = $this->getFileRepositoryCollection()->get($repositoryName);

        /** @todo Check param for existence */
        $id = $this->params('id');

        if (strlen($id) === 0) {
            throw new InvalidParamException('Invalid or no value for required parameter "id"');
        }

//        if ($derivative !== null) {
//            $id .= '/' . $derivative;
//        }

        /** @var Response $response */
        $response = $this->getResponse();

        try {
            $item = $repository->getItem($id);

        } catch (ItemNotFoundException $e) {
            $response->setStatusCode(404);
            return $response;
        }

        $type = $item->getType();
        $size = $item->getSize();

//        if ($type === null) {
//            $info = new \finfo(FILEINFO_MIME);
//            $type = $info->file($this->getPath());
//        }

        if ($type === null) {
            $type = 'application/octet-stream';
        }

        header('Content-Type: ' . $type);

        if ($size > 0) {
            header('Content-Length: ' . $size);
        }

        if ($forceDownload === true) {
            $name = $item->getName();

            if ($name !== null) {
                header('Content-Disposition: attachment; filename="' . utf8_decode($name) . '"');

            } else {
                header('Content-Disposition: attachment');
            }
        }

//        if ($expireIn > 0) {
//            header('Pragma: public');
//            header('Cache-Control: maxage=' . $expireIn);
//            header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + $expireIn));
//        }

        /** @todo Work with stream */
        $response->setContent($item->getContents());

        return $response;
    }
}
