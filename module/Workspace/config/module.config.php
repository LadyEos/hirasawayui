<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Workspace\Controller\Workspace' => 'Workspace\Controller\WorkspaceController',
            'Workspace\Controller\Song' => 'Workspace\Controller\SongController',
            'Workspace\Controller\Version' => 'Workspace\Controller\VersionController'
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
                        'action' => 'workspace'
                    )
                ),
                'may_terminate' => true
            ),
            'song' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/song[/][:action][/:id][/:type][/:name][/:oid]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                        'name' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'type' => 'sample',
                        'oid' => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Workspace\Controller\Song',
                        'action' => 'index'
                    ),
                    'may_terminate' => true
                ),
                'may_terminate' => true,
                'child_routes' => array(
                )
            ),
            'version' => array(
        		'type' => 'segment',
        		'options' => array(
    				'route' => '/version[/][:action][/:id]',
    				'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+'
    				),
    				'defaults' => array(
						'controller' => 'Workspace\Controller\Version',
						'action' => 'index'
    				)
        		),
        		'may_terminate' => true
            )
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'workspace' => __DIR__ . '/../view'
        )
    )
);