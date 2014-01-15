<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Users\Controller\Users' => 'Users\Controller\UsersController'
        )
    ),
    
    'router' => array(
        'routes' => array(
            'users' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/users[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Users\Controller\Users',
                        'action' => 'index'
                    )
                ),
            ),
            'zfcuser' => array(
                'child_routes' => array(
                    'addprofile' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/addprofile',
                            'defaults' => array(
                                'controller' => 'Users\Controller\Users',
                                'action' => 'addProfile'
                            )
                        )
                    ),
                    'home' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/home',
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