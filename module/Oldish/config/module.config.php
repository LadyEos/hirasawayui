<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Oldish\Controller\Oldish' => 'Oldish\Controller\OldishController',
        )
    ),
    'router' => array(
        'routes' => array(
            'oldish' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/oldish[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Oldish\Controller\Oldish',
                        'action' => 'index'
                    )
                )
            ),
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'oldish' => __DIR__ . '/../view'
        )
    )
);