<?php

return array(
    'router' => array(
        'routes' => array(
            'file' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/file',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Detail\File\Controller',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'download' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/download/:repository/:id',
                            'defaults' => array(
                                'controller' => 'File',
                                'action'     => 'download',
                            ),
//                            'constraints' => array(
//                                'id' => '(.)+',
//                            ),
                        ),
                    ),
                    'show' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/show/:repository/:id',
                            'defaults' => array(
                                'controller' => 'File',
                                'action'     => 'show',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
        ),
        'aliases' => array(
        ),
        'invokables' => array(
        ),
        'factories' => array(
            'Detail\File\BackgroundProcessing\Driver\Bernard\BernardService' => 'Detail\File\Factory\BackgroundProcessing\Driver\BernardServiceFactory',
            'Detail\File\BackgroundProcessing\Driver\Bernard\BernardDriver'  => 'Detail\File\Factory\BackgroundProcessing\Driver\BernardDriverFactory',
            'Detail\File\Options\ModuleOptions'                              => 'Detail\File\Factory\Options\ModuleOptionsFactory',
            'Detail\File\Service\RepositoryService'                          => 'Detail\File\Factory\Service\RepositoryServiceFactory',
        ),
        'initializers' => array(
        ),
        'shared' => array(
        ),
    ),
    'controllers' => array(
        'initializers' => array(
        ),
        'invokables' => array(
        ),
        'factories' => array(
            'Detail\File\Controller\File' => 'Detail\File\Factory\Controller\FileControllerFactory',
        ),
    ),
    'detail_file' => array(
        'background_drivers' => array(
            'bernard' => array(
                'create_queue_name' => 'file-create',
                'complete_queue_name' => 'file-complete',
                'producer' => 'Bernard\Producer',
//                'message_factory' => 'Detail\File\BackgroundProcessing\Message\MessageFactory',
            ),
        ),
    ),
);
