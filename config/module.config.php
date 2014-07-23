<?php

return array(
    'service_manager' => array(
        'abstract_factories' => array(
        ),
        'aliases' => array(
        ),
        'invokables' => array(
        ),
        'factories' => array(
            'Detail\File\Options\ModuleOptions'     => 'Detail\File\Factory\Options\ModuleOptionsFactory',
            'Detail\File\Service\RepositoryService' => 'Detail\File\Factory\Service\RepositoryServiceFactory',
        ),
        'initializers' => array(
        ),
        'shared' => array(
        ),
    ),
);
