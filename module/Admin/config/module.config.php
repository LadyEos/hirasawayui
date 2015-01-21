<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Admin\Controller\Admin' => 'Admin\Controller\AdminController'
        )
    ),
    'router' => array(
        'routes' => array(
            'zfcadmin' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/admin',
                    'defaults' => array(
                        'controller' => 'ZfcAdmin\Controller\AdminController',
                        'action' => 'index'
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'users' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/users[/page/:page]',
                            'constraints' => array(
                                'page' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Admin',
                                'action' => 'users'
                            )
                        )
                    ),
                    'role' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/role[/:act][/:id]',
                            'constraints' => array(
                                'act' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Admin',
                                'action' => 'role'
                            )
                        )
                    ),
                    'songs' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/songs[/page/:page]',
                            'constraints' => array(
                                'page' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Admin',
                                'action' => 'songs'
                            )
                        )
                    ),
                    'price' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/price[/:id]',
                            'constraints' => array(
                                'id' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Admin',
                                'action' => 'price'
                            )
                        )
                    ),
                    'newrole' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/newrole[/:id]',
                            'constraints' => array(
                                'id' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Admin',
                                'action' => 'newrole'
                            )
                        )
                    )
                )
            )
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'admin' => __DIR__ . '/../view'
        )
    )
);