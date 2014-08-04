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
//            'Detail\File\Factory\Resolver\ResolverAbstractFactory',
        ),
        'aliases' => array(
        ),
        'invokables' => array(
            'Detail\File\BackgroundProcessing\Message\MessageFactory' => 'Detail\File\BackgroundProcessing\Message\MessageFactory',
        ),
        'factories' => array(
            'Detail\File\BackgroundProcessing\Bernard\Receiver\CreateItemReceiver' => 'Detail\File\Factory\BackgroundProcessing\Bernard\Receiver\CreateItemReceiverFactory',
            'Detail\File\BackgroundProcessing\Driver\Bernard\BernardDriver'        => 'Detail\File\Factory\BackgroundProcessing\Driver\BernardDriverFactory',
            'Detail\File\Options\BackgroundProcessing\Driver\BernardDriverOptions' => 'Detail\File\Factory\Options\BackgroundProcessing\Driver\BernardDriverOptionsFactory',
            'Detail\File\Options\ModuleOptions'                                    => 'Detail\File\Factory\Options\ModuleOptionsFactory',
            'Detail\File\Service\RepositoryService'                                => 'Detail\File\Factory\Service\RepositoryServiceFactory',
        ),
        'initializers' => array(
        ),
        'shared' => array(
            // Message factories are primarily used for Detail\Bernard\Message\Messenger.
            // We want each Messenger to have it's own factory.
            'Detail\File\BackgroundProcessing\Message\MessageFactory' => false,
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
                'create_queue_name' => 'create-item',
                'complete_queue_name' => 'complete-item',
                'messenger' => 'bernard.messenger.detail_file',
            ),
        ),
        // Type/class mapping for the storage factories
        'storage_factories' => array(
            'gaufrette' => 'Detail\File\Factory\Storage\GaufretteStorageFactory',
        ),
        // Type/class mapping for the resolver factories
        'resolver_factories' => array(
            'prefix' => 'Detail\File\Factory\Resolver\PrefixResolverFactory',
        ),
    ),
    'bernard' => array(
        'messengers' => array(
            'bernard.messenger.detail_file' => array(
                'message_factory' => 'Detail\File\BackgroundProcessing\Message\MessageFactory',
                'producer' => 'Bernard\Producer',
            ),
        ),
        'receivers' => array(
            'create-item' => 'Detail\File\BackgroundProcessing\Bernard\Receiver\CreateItemReceiver',
        ),
    ),
);
