<?php
return array(
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host' => 'localhost',
                    'port' => '3306',
                    'user' => 'arzplane_ml',
                    'password' => 'mus1cl4ck3y',
                    'dbname' => 'arzplane_musiclackey'
                )
            )
        )
    )
);