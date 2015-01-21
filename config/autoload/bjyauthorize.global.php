<?php
return array(
    'bjyauthorize' => array(
        'resource_providers' => array(
    		'BjyAuthorize\Provider\Resource\Config' => array(
    			'application' => array(
    			     'logout' => array(),
    			     'login' => array(),
    			     'register' => array()
    		    ),
    		    'store' => array(),
    		    'user' => array(),
    		    'workspace' => array(),
    		    'admin' => array(),
    		    'search' => array(),
    		    'messages' => array(),
    		    'dj' => array()
    		)
        ),
        'rule_providers' => array(
    		'BjyAuthorize\Provider\Rule\Config' => array(
				'allow' => array(
					array(array('user','guest'), 'application', 'view'),
				    array(array('admin'), 'admin', 'view'),
				    array(array('user','guest'), 'store', 'view'),
				    array(array('user'), 'user', 'view'),
				    array(array('PLy','PCo','PVo'), 'workspace', 'view'),
    		        array(array('user'), 'logout', 'logout'),
				    array(array('guest'), 'login', 'login'),
				    array(array('guest'), 'register', 'register'),
				    array(array('user'), 'store', 'buy'),
				    array(array('user'), 'store', 'download'),
				    array(array('user','guest'), 'store', 'list'),
				    array(array('user','guest'), 'search', 'search'),
				    array(array('user'), 'messages', 'messages')
				)
    		)
        ),

        /* Currently, only controller and route guards exist
        * Consider enabling either the controller or the route guard depending on your needs.
        */
		'guards' => array(
		    'BjyAuthorize\Guard\Controller' => array(
	    		array('controller' => 'Application\Controller\Index','roles' => array('guest','user')),
	    		array('controller' => 'Users\Controller\Users', 'action' => 'register', 'roles' => array('guest')),
	            array('controller' => 'zfcuser', 'action' => 'login', 'roles' => array('guest')),
	            array('controller' => 'zfcuser','roles' => array('user')),
		        array('controller' => 'Users\Controller\Users', 'action'=> 'index','roles' => array('user')),
	            array('controller' => 'Users\Controller\Users','roles' => array('user')),
		        array('controller' => 'Avatar\Controller\Avatar','roles' => array('user')),
		        array('controller' => 'Store\Controller\Store','roles' => array('guest','user')),
		        array('controller' => 'Store\Controller\Download','roles' => array('user')),
		        array('controller' => 'Users\Controller\Fellowship','roles' => array('user')),
		        array('controller' => 'Users\Controller\Profile','roles' => array('user')),
		        array('controller' => 'Users\Controller\Payment','roles' => array('user')),
		        array('controller' => 'Workspace\Controller\Workspace','roles' => array('PLy','PCo','PVo')),
		        array('controller' => 'Workspace\Controller\Song','roles' => array('user')),
		        //array('controller' => 'Workspace\Controller\Song','action'=>'list','roles' => array('user')),
		        array('controller' => 'Workspace\Controller\Version','roles' => array('user')),
		        array('controller' => 'Workspace\Controller\Album','roles' => array('user')),
		        array('controller' => 'Search\Controller\Search','roles' => array('user','guest')),
		        array('controller' => 'Messages\Controller\Messages','roles' => array('user')),
	    		//backend
	    		array('controller' => 'Admin\Controller\Admin', 'roles' => array('admin')),
		        array('controller' => 'ZfcAdmin\Controller\AdminController', 'roles' => array('admin')),
		        array('controller' => 'Admin\Controller\AdminController','roles' => array('admin')),
		    )
        )
    )
);