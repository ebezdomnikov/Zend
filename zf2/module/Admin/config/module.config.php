<?php

namespace Admin;

  return array(
      'view_manager' => array(
          'display_not_found_reason' => true,
          'display_exceptions'       => true,
          'doctype'                  => 'HTML5',
          'not_found_template'       => 'error/404',
          'exception_template'       => 'error/index',
          'template_map' => array(
              'zfc-user/user/register' => __DIR__ . '/../view/zfc-user/user/register.phtml',
              'zfc-user/user/index' => __DIR__ . '/../view/zfc-user/user/index.phtml',
              'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
              'admin/index/index' =>    __DIR__ . '/../view/admin/index/index.phtml',
              'error/404'               => __DIR__ . '/../view/error/404.phtml',
              'error/index'             => __DIR__ . '/../view/error/index.phtml',
          ),
          'template_path_stack' => array(
              __DIR__ . '/../view',
              'zfc-user' => '/../view/zfc-user'
          ),

      ),
      'controllers' => array(
          'invokables' => array(
              'Admin\Controller\Activation' => 'Admin\Controller\ActivationController',
          ),
      ),
      'router' => array(
          'routes' => array(
              'activation' => array(
                  'type' => 'segment',
                  'options' => array(
                      'route'    => '/activation/[:token]',
                      'constraints' => array(
                          'token' => '[a-zA-Z0-9_]+',
                      ),
                      'defaults' => array(
                          'controller' => 'Admin\Controller\Activation',
                          'action'     => 'index',
                      ),
                  ),
              ),
          ),
      ),
      // Doctrine config
      'doctrine' => array(
          'driver' => array(
              __NAMESPACE__ . '_driver' => array(
                  'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                  'cache' => 'array',
                  'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
              ),
              'orm_default' => array(
                  'drivers' => array(
                      __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                  )
              )
          )
      )
  );
