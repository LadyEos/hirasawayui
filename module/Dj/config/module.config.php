<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Dj\Controller\Dj' => 'Dj\Controller\DjController',
        )
    ),
    'router' => array(
        'routes' => array(
            'dj' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/dj[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Dj\Controller\Dj',
                        'action' => 'dj'
                    )
                )
            ),
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'dj' => __DIR__ . '/../view'
        )
    )
);