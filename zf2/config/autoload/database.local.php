<?php
return array(
    'db' => array(
        'driver'    => 'PdoMysql',
        'hostname'  => '127.0.0.1',
        'database'  => 'zf',
        'username'  => 'root',
        'password'  => '',
    ),
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),
    ),
    'doctrine' => array(
        'connection' => array(
            // default connection name
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host'     => 'localhost',
                    'port'     => '3306',
                    'user'     => 'root',
                    'password' => '',
                    'dbname'   => 'zf',
                )
            )
        )
    ),
);