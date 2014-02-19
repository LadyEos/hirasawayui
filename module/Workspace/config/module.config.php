<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Workspace\Controller\Workspace' => 'Workspace\Controller\WorkspaceController',
            'Workspace\Controller\Song' => 'Workspace\Controller\SongController'
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
                    'route' => '/song[/][:action][/:id][/:type][/:name]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                        'name' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'type' => 'sample'
                    ),
                    'defaults' => array(
                        'controller' => 'Workspace\Controller\Song',
                        'action' => 'index'
                    ),
                    'may_terminate' => true
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'lyricsSample' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/sample/lyrics',
                            'defaults' => array(
                                'controller' => 'Workspace\Controller\Song',
                                'action' => 'uploadlyrics'
                            )
                        )
                    )
                )
            )
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'workspace' => __DIR__ . '/../view'
        )
    )
);