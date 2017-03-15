<?php

namespace Admin;

use Admin\Listeners\AfterUserRegister;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module implements ServiceProviderInterface
{
    protected $serviceLocator;

    protected $mvcEvent;

    protected $router;

    public function onBootstrap(MvcEvent $e)
    {
        $this->mvcEvent = $e;

        $this->router = $e->getRouter();

        $e->getApplication()->getServiceManager()->get('translator');
        $eventManager        = $e->getApplication()->getEventManager();

        $em = $eventManager->getSharedManager();
        $em->attach('Admin\Service\User', 'register.post', array($this, 'onAfterUserRegister'));

        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }



    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig() {
        return array(
            'factories' => array(
                'zfcuser_index_form' => 'Admin\Factory\Form\Index',
                'zfcuser_register_form' => 'Admin\Factory\Form\Register',
                'zfcuser_user_service'  => 'Admin\Factory\Service\UserFactory',
            ),
        );
    }

    public function getControllerConfig()
    {
        return array(
            'factories' => array(
                'zfcuser' => 'Admin\Factory\Controller\UserControllerFactory'
            ),
        );
    }


    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function onAfterUserRegister($event)
    {
        $user = $event->getParam('user');

        $listener = new AfterUserRegister($this->mvcEvent, $this->router);

        $listener->exec($user);
    }

}
