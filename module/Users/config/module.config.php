<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Users\Controller\Profile' => 'Users\Controller\ProfileController',
            'Users\Controller\Users' => 'Users\Controller\UsersController'
        )
    )
    ,
    
    'router' => array(
        'routes' => array(
            /*'users' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/user[/][:action][/][:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Users\Controller\Users',
                        'action' => 'index'
                    )
                ),
                'child_routes' => array(
                    
                )
            ),*/
            'profile' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/profile[/][:action][/][user/:id][user/:username]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                        'username' => '[a-zA-Z][a-zA-Z0-9_-]*'
                    ),
                    'defaults' => array(
                        'controller' => 'Users\Controller\Profile',
                        'action' => 'index'
                    )
                ),
                /*
                'child_routes' => array(
                    'profiles' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/profile/user/[:username][/][:id]',
                            'constraints' => array(
                                'username' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]+'
                            // 'username' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                                        ),
                            'defaults' => array(
                                'controller' => 'Users\Controller\Profile',
                                'action' => 'profile'
                            )
                        )
                    )
                )*/
            ),
            'zfcuser' => array(
                'child_routes' => array(
                    
                    /*'add' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/add',
                            'defaults' => array(
                                'controller' => 'Users\Controller\Users',
                                'action' => 'add'
                            )
                        )
                    ),
                    'edit' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/edit',
                            'defaults' => array(
                                'controller' => 'Users\Controller\Users',
                                'action' => 'edit'
                            )
                        )
                    ),*/
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