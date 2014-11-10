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
            'Detail\File\Factory\Repository\RepositoryFactory'        => 'Detail\File\Factory\Repository\RepositoryFactory',
            'Detail\File\Factory\Resolver\PrefixResolverFactory'      => 'Detail\File\Factory\Resolver\PrefixResolverFactory',
            'Detail\File\Factory\Resolver\StorageResolverFactory'     => 'Detail\File\Factory\Resolver\StorageResolverFactory',
            'Detail\File\Factory\Storage\GaufretteStorageFactory'     => 'Detail\File\Factory\Storage\GaufretteStorageFactory',
        ),
//        'delegators' => array(
//            'Detail\File\Service\RepositoryService' => array(
//                'Zend\ServiceManager\Proxy\LazyServiceFactory'
//            ),
//        ),
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
//    'lazy_services' => array(
//        'class_map' => array(
//            'Detail\File\Service\RepositoryService' => 'Detail\File\Service\RepositoryService',
//        ),
//    ),
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
                'create_queue' => array(
                    'name' => 'create-item',
                    'options' => array(),
                ),
                'complete_queue' => array(
                    'name' => 'complete-item',
                    'options' => array(
//                        'subscribers' => array(
//                            array(
//                                'url' => 'http://localhost/user/apply-image', // Probably won't work (local config required)
//                                'headers' => array(
//                                    'Content-Type' => 'application/json',
//                                ),
//                            ),
//                        ),
//                        'push_type' => 'unicast',
//                        'retries' => 2,
//                        'retries_delay' => 5,
//                        'error_queue' => 'errors',
                    ),
                ),
                'messenger' => 'bernard.messenger.detail_file',
            ),
        ),
        'repository_factories' => array(
            'default' => 'Detail\File\Factory\Repository\RepositoryFactory',
        ),
        // Type/class mapping for the storage factories
        'storage_factories' => array(
            'gaufrette' => 'Detail\File\Factory\Storage\GaufretteStorageFactory',
        ),
        // Type/class mapping for the resolver factories
        'resolver_factories' => array(
            'prefix'  => 'Detail\File\Factory\Resolver\PrefixResolverFactory',
            'storage' => 'Detail\File\Factory\Resolver\StorageResolverFactory',
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
