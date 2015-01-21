<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Users\Controller\Profile' => 'Users\Controller\ProfileController',
            'Users\Controller\Users' => 'Users\Controller\UsersController',
            'Users\Controller\Fellowship' => 'Users\Controller\FellowshipController',
            'Users\Controller\Payment' => 'Users\Controller\PaymentController'
        )
    ),
    
    'router' => array(
        'routes' => array(
            'profile' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/profile[/:action][/user/:id][/user/:username]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                        'username' => '[a-zA-Z][a-zA-Z0-9_-]*'
                    ),
                    'defaults' => array(
                        'controller' => 'Users\Controller\Profile',
                        'action' => 'index'
                    )
                )
            ),
            'fellowship' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/fellowship[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Users\Controller\Fellowship',
                        'action' => 'index'
                    )
                ),
                'may_terminate' => true
            ),
            'payment' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/payment[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Users\Controller\Payment',
                        'action' => 'index'
                    )
                )
            ),
            'zfcuser' => array(
                'type' => 'Literal',
                'priority' => 1000,
                'options' => array(
                    'route' => '/user',
                    'defaults' => array(
                        'controller' => 'Users\Controller\Users',
                        'action' => 'index'
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'home' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/[home]',
                            'defaults' => array(
                                'controller' => 'Users\Controller\Users',
                                'action' => 'index'
                            )
                        )
                    ),
                    'register' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/register',
                            'defaults' => array(
                                'controller' => 'Users\Controller\Users',
                                'action' => 'register'
                            )
                        )
                    ),
                    'chooserole' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/choose',
                            'defaults' => array(
                                'controller' => 'Users\Controller\Users',
                                'action' => 'chooserole'
                            )
                        )
                    ),
                    'lyricist' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/lyricist',
                            'defaults' => array(
                                'controller' => 'Users\Controller\Users',
                                'action' => 'lyricist'
                            )
                        )
                    ),
                    'vocalist' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/vocalist',
                            'defaults' => array(
                                'controller' => 'Users\Controller\Users',
                                'action' => 'vocalist'
                            )
                        )
                    ),
                    'composer' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/composer',
                            'defaults' => array(
                                'controller' => 'Users\Controller\Users',
                                'action' => 'composer'
                            )
                        )
                    ),
                    'basic' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/basic',
                            'defaults' => array(
                                'controller' => 'Users\Controller\Users',
                                'action' => 'basic'
                            )
                        )
                    ),
                    'deleterole' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/deleterole',
                            'defaults' => array(
                                'controller' => 'Users\Controller\Users',
                                'action' => 'deleterole'
                            )
                        )
                    )
                )
            )
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'users' => __DIR__ . '/../view'
        )
    )
);