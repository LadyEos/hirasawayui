<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Messages\Controller\Messages' => 'Messages\Controller\MessagesController',
        )
    ),
    'router' => array(
        'routes' => array(
            'messages' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/messages[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Messages\Controller\Messages',
                        'action' => 'inbox'
                    )
                )
            ),
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'messages' => __DIR__ . '/../view'
        )
    )
);