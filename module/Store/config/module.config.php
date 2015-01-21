<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Store\Controller\Store' => 'Store\Controller\StoreController',
            'Store\Controller\Download' => 'Store\Controller\DownloadController'
        )
    ),
    'router' => array(
        'routes' => array(
            'store' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/store[/:action][/:id][/page/:page][/token/:token]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Store\Controller\Store',
                        'action' => 'index',
                        'page' => 1
                    )
                ),
                'may_terminate' => true,
                /*'child_routes'=>array(
                    'cart' => array(
                		'type' => 'Literal',
                		'options' => array(
            				'route' => '/cart',
            				'defaults' => array(
        						'controller' => 'Store\Controller\Store',
        						'action' => 'cart'
            				)
                		)
                    ),
                    'may_terminate' => true,
                )*/
            ),
            'download' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/download[/:action][/:id][/:key]',
                    'constraints' => array(
                    		'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    		'id' => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Store\Controller\Download',
                        'action' => 'index'
                    ),
                    'may_terminate' => true,
                ),
                'may_terminate' => true,
                /*'child_routes' => array(
            		'file' => array(
        				'type' => 'segment',
        				'options' => array(
        				    'route' => '/file[/:key]',
        				    'constraints' => array(
        				    		'key' => '[a-zA-Z][a-zA-Z0-9_-]*'
        				    ),
    						'defaults' => array(
    								'controller' => 'Store\Controller\Download',
    								'action' => 'download'
    						)
        				)
            		),
                    'index' => array(
                    		'type' => 'literal',
                    		'options' => array(
                    				'route' => '/index',
                    				'defaults' => array(
                    						'controller' => 'Store\Controller\Download',
                    						'action' => 'index'
                    				)
                    		)
                    )
                )*/
            )
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'store' => __DIR__ . '/../view'
        )
    )
);

/*'child_routes'=>array(
 'paginator' => array(
 		'type' => 'segment',
 		'options' => array(
 				'route' => '/list/[page/:page]',
 				'defaults' => array(
 						'page' => 1,
 				),
 		),
 ),
)*/