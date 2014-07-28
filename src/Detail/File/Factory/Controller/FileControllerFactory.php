<?php

namespace Detail\File\Factory\Controller;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

use Detail\File\Controller\FileController;

class FileControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        /** @var \Zend\Mvc\Controller\ControllerManager $controllerManager */

        /** @var \Detail\File\Repository\RepositoryCollectionInterface $repositories */
        $repositories = $controllerManager->getServiceLocator()->get('Detail\File\Service\RepositoryService');

        $controller = new FileController($repositories);

        return $controller;
    }
}
