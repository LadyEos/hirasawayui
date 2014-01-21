<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Users\Controller\Users' => 'Users\Controller\UsersController',
            'Users\Controller\Avatars' => 'Users\Controller\AvatarsController'
        )
    ),
    
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
            'zfcuser' => array(
            		'child_routes' => array(
            				'default' => array(
            						'type' => 'Segment',
            						'options' => array(
            								'route' => '/user[/][:action][/:id]',
            								'constraints' => array(
            										'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
            										'id' => '[0-9]+'
            								),
            								'defaults' => array(
            										'controller' => 'Users\Controller\Users',
            										'action' => 'index'
            								)
            						)
            				),
            		    'addprofile' => array(
            		    		'type' => 'Literal',
            		    		'options' => array(
            		    				'route' => '/addprofile',
            		    				'defaults' => array(
            		    						'controller' => 'Users\Controller\Users',
            		    						'action' => 'addprofile'
            		    				)
            		    		)
            		    ),
            		    'editprofile' => array(
            		    		'type' => 'Literal',
            		    		'options' => array(
            		    				'route' => '/editprofile',
            		    				'defaults' => array(
            		    						'controller' => 'Users\Controller\Users',
            		    						'action' => 'editprofile'
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