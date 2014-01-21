<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Avatar\Controller\Avatar' => 'Avatar\Controller\AvatarController',
        )
    ),
    'router' => array(
        'routes' => array(
            'avatar' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/avatar[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Avatar\Controller\Avatar',
                        'action' => 'index'
                    )
                )
            ),
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'avatar' => __DIR__ . '/../view'
        )
    )
);