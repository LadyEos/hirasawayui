<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Workspace\Controller\Workspace' => 'Workspace\Controller\WorkspaceController',
        )
    ),
    'router' => array(
        'routes' => array(
            'workspace' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/workspace[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Workspace\Controller\Workspace',
                        'action' => 'index'
                    )
                )
            ),
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'workspace' => __DIR__ . '/../view'
        )
    )
);