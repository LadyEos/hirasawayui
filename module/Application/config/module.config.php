<?php
namespace Application;

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license http://framework.zend.com/license/new-bsd New BSD License
 */
return array(
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController'
        )
    ),
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action' => 'index'
                    )
                )
            ),
            'test' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/app/test',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action' => 'testdoctrine'
                    )
                )
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Index',
                        'action' => 'index'
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                            ),
                            'defaults' => array()
                        )
                    )
                )
            )
        )
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory'
        ),
        'factories' => array(
            'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory'
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator'
        )
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo'
            )
        )
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
            'pagination/sliding' => __DIR__ . '/../view/pagination/pagination.phtml',
            'pagination/get' => __DIR__ . '/../view/pagination/paginationget.phtml'
        // 'audioplayer/big'=>__DIR__ . '/../view/audioplayer/audioplayer-big.phtml'
                ),
        'template_path_stack' => array(
            __DIR__ . '/../view'
        )
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array()
        )
    ),
    'doctrine' => array(
        /**
         * 'driver' => array(
         * __NAMESPACE__ .
         *
         *
         *
         *
         * '_driver' => array(
         * 'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
         * 'cache' => 'array',
         * 'paths' => array(
         * __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity'
         * )
         * ),
         * 'orm_default' => array(
         * 'drivers' => array(
         * __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
         * )
         * )
         * ),*
         */
        'driver' => array(
            'zfcuser_entity' => array(
                // customize path
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => array(
                    __DIR__ . '/../src/Application/Entity'
                )
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Application\Entity' => 'zfcuser_entity'
                )
            )
        ),
        'authentication' => array(
            'orm_default' => array(
                'object_manager' => 'Doctrine\ORM\EntityManager',
                'identity_class' => 'Application\Entity\Users',
                'identity_property' => 'username',
                'credential_callable' => 'Application\Service\AuthService::verifyHashedPassword'
            )
        )
    ),
    'zfcuser' => array(
        // telling ZfcUser to use our own class
        'user_entity_class' => 'Application\Entity\Users',
        // telling ZfcUserDoctrineORM to skip the entities it defines
        'enable_default_entities' => false
    ),
    'bjyauthorize' => array(
        // Using the authentication identity provider, which basically reads the roles from the auth service's identity
        'default_role' => 'guest', // not authenticated
        'authenticated_role' => 'user',
        'identity_provider' => 'BjyAuthorize\Provider\Identity\AuthenticationIdentityProvider',
        
        'role_providers' => array(
            // using an object repository (entity repository) to load all roles into our ACL
            'BjyAuthorize\Provider\Role\ObjectRepositoryProvider' => array(
                'object_manager' => 'doctrine.entitymanager.orm_default',
                'role_entity_class' => 'Application\Entity\Role'
            )
        )
    ),
    'musicLakey' => array(),
    'navigation' => array(
        'default' => array(
            array(
                'label' => 'Home',
                'route' => 'home'
            ),
            array(
                'label' => 'Register',
                'route' => 'zfcuser/register',
                'action' => 'register',
                'resource' => 'register',
                'privilege' => 'register'
            ),
            array(
                'label' => 'Login',
                'route' => 'zfcuser/login',
                'action' => 'login',
                'resource' => 'login',
                'privilege' => 'login'
            ),
            array(
                'label' => 'Logout',
                'route' => 'zfcuser/logout',
                'action' => 'logout',
                'resource' => 'logout',
                'privilege' => 'logout'
            ),
            array(
                'label' => 'Admin',
                'route' => 'zfcadmin',
                'resource' => 'admin',
                'privilege' => 'view',
                'pages' => array(
                    array(
                        'label' => 'Admin Panel',
                        'route' => 'zfcadmin',
                        'action' => 'index',
                        'resource' => 'admin',
                        'privilege' => 'view'
                    ),
                    array(
                        'label' => 'Users',
                        'route' => 'zfcadmin/users',
                        'action' => 'users',
                        'resource' => 'admin',
                        'privilege' => 'view',
                        'pages' => array(
                            array(
                                'label' => 'Role',
                                'route' => 'zfcadmin/role',
                                'action' => 'role',
                                'resource' => 'admin',
                                'privilege' => 'view'
                            )
                        )
                    ),
                    array(
                        'label' => 'Songs',
                        'route' => 'zfcadmin/songs',
                        'action' => 'songs',
                        'resource' => 'admin',
                        'privilege' => 'view',
                        'pages' => array(
                            array(
                                'label' => 'Price',
                                'route' => 'zfcadmin/price',
                                'action' => 'price',
                                'resource' => 'admin',
                                'privilege' => 'view'
                            )
                        )
                    ),
                    array(
                        'label' => 'Create Roles',
                        'route' => 'zfcadmin/newrole',
                        'action' => 'newrole',
                        'resource' => 'admin',
                        'privilege' => 'view'
                    )
                )
            ),
            array(
                'label' => 'Store',
                'route' => 'store',
                'resource' => 'store',
                'privilege' => 'view',
                'pages' => array(
                    array(
                        'label' => 'Store',
                        'route' => 'store',
                        'action' => 'list',
                        'resource' => 'store',
                        'privilege' => 'list'
                    ),
                    array(
                        'label' => 'Albums',
                        'route' => 'store',
                        'action' => 'album',
                        'resource' => 'store',
                        'privilege' => 'list'
                    ),
                    array(
                        'label' => 'Cart',
                        'route' => 'store',
                        'action' => 'cart',
                        'resource' => 'store',
                        'privilege' => 'buy'
                    ),
                    array(
                        'label' => 'Download',
                        'route' => 'download',
                        'resource' => 'store',
                        'privilege' => 'download',
                        'pages' => array(
                            array(
                                'label' => 'Index',
                                'route' => 'download',
                                'action' => 'index'
                            ),
                            array(
                                'label' => 'Donwload',
                                'route' => 'download',
                                'action' => 'file'
                            )
                        )
                    ),
                    array(
                        'label' => 'Purchase History',
                        'route' => 'store',
                        'action' => 'history',
                        'resource' => 'store',
                        'privilege' => 'buy'
                    ),
                    array(
                        'label' => 'Top 10',
                        'route' => 'search',
                        'action' => 'index',
                        'resource' => 'store',
                        'privilege' => 'list'
                    )
                )
            ),
            array(
                'label' => 'User',
                'route' => 'zfcuser',
                'resource' => 'user',
                'privilege' => 'view',
                'pages' => array(
                    array(
                        'label' => 'User Panel',
                        'route' => 'zfcuser',
                        'action' => 'index',
                        'pages' => array(
                            array(
                                'label' => 'User Panel',
                                'route' => 'zfcuser/home',
                                'action' => 'index'
                            ),
                            array(
                                'label' => 'Change Password',
                                'route' => 'zfcuser/changepassword',
                                'action' => 'changepassword'
                            ),
                            array(
                                'label' => 'Change Email',
                                'route' => 'zfcuser/changeemail',
                                'action' => 'changeemail'
                            ),
                            array(
                                'label' => 'Role',
                                'route' => 'zfcuser/chooserole',
                                'action' => 'chooserole'
                            )
                        )
                    ),
                    array(
                        'label' => 'Profile',
                        'route' => 'profile',
                        'action' => 'index',
                        'pages' => array(
                            array(
                                'label' => 'Add Profile',
                                'route' => 'profile',
                                'action' => 'add'
                            ),
                            array(
                                'label' => 'Edit Profile',
                                'route' => 'profile',
                                'action' => 'edit'
                            ),
                            array(
                                'label' => 'Avatar',
                                'route' => 'avatar',
                                'pages' => array(
                                    array(
                                        'label' => 'Upload',
                                        'route' => 'avatar',
                                        'action' => 'upload'
                                    )
                                )
                            )
                        )
                    ),
                    array(
                        'label' => 'Friends Feed',
                        'route' => 'fellowship',
                        'action' => 'feed'
                    ),
                    array(
                        'label' => 'Get Paid!',
                        'route' => 'payment',
                        'action' => 'index',
                        'pages' => array(
                            array(
                                'label' => 'Add Payment Options',
                                'route' => 'payment',
                                'action' => 'add'
                            ),
                            array(
                                'label' => 'Edit Payment Options',
                                'route' => 'payment',
                                'action' => 'edit'
                            )
                        )
                    )
                )
            ),
            array(
                'label' => 'Workspace',
                'route' => 'workspace',
                'resource' => 'workspace',
                'privilege' => 'view',
                'pages' => array(
                    array(
                        'label' => 'Workspace',
                        'route' => 'workspace',
                        'action' => 'workspace'
                    ),
                    array(
                        'label' => 'Song',
                        'route' => 'song',
                        'pages' => array(
                            array(
                                'label' => 'View',
                                'route' => 'song',
                                'action' => 'view'
                            ),
                            array(
                                'label' => 'Edit',
                                'route' => 'song',
                                'action' => 'edit'
                            ),
                            array(
                                'label' => 'Add',
                                'route' => 'song',
                                'action' => 'add'
                            ),
                            array(
                                'label' => 'Delete',
                                'route' => 'song',
                                'action' => 'delete'
                            ),
                            array(
                                'label' => 'Upload',
                                'route' => 'song',
                                'action' => 'upload'
                            ),
                            array(
                                'label' => 'Lyrics',
                                'route' => 'song',
                                'action' => 'lyrics'
                            ),
                            array(
                                'label' => 'Collaboration',
                                'route' => 'song',
                                'action' => 'collaboration'
                            ),
                            array(
                                'label' => 'Collaborate',
                                'route' => 'song',
                                'action' => 'collaborate'
                            ),
                            array(
                                'label' => 'Version',
                                'route' => 'version',
                                'pages' => array(
                                    array(
                                        'label' => 'Add',
                                        'route' => 'version',
                                        'action' => 'add'
                                    ),
                                    array(
                                        'label' => 'Past Versions',
                                        'route' => 'version',
                                        'action' => 'past'
                                    )
                                )
                            )
                        )
                    ),
                    array(
                        'label' => 'Album',
                        'route' => 'album',
                        'pages' => array(
                            array(
                                'label' => 'Add',
                                'route' => 'album',
                                'action' => 'add'
                            ),
                            array(
                                'label' => 'Delete',
                                'route' => 'album',
                                'action' => 'delete'
                            ),
                            array(
                                'label' => 'Edit',
                                'route' => 'album',
                                'action' => 'edit'
                            ),
                            array(
                                'label' => 'View',
                                'route' => 'album',
                                'action' => 'view'
                            )
                        )
                    )
                )
            ),
            array(
                'label' => 'Search',
                'route' => 'search',
                'resource' => 'search',
                'privilege' => 'search',
                'pages' => array(
                    array(
                        'label' => 'Users',
                        'route' => 'search',
                        'action' => 'users'
                    ),
                    array(
                        'label' => 'Projects',
                        'route' => 'search',
                        'action' => 'projects'
                    ),
                    array(
                        'label' => 'Albums',
                        'route' => 'search',
                        'action' => 'albums'
                    )
                )
            ),
            array(
                'label' => 'Messages',
                'route' => 'messages',
                'resource' => 'messages',
                'privilege' => 'messages',
                'pages' => array(
                    array(
                        'label' => 'Inbox',
                        'route' => 'messages',
                        'action' => 'inbox'
                    ),
                    array(
                        'label' => 'Send a Message',
                        'route' => 'messages',
                        'action' => 'send'
                    )
                )
            )
        )
    )
);