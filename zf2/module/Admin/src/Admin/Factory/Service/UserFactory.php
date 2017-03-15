<?php

namespace Admin\Factory\Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use Admin\Service\User;

class UserFactory extends \ZfcUser\Factory\Service\UserFactory
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $service = new User();
        $service->setServiceManager($serviceLocator);
        return $service;
    }
}
