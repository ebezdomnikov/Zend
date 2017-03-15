<?php

namespace Admin\Controller;

use Admin\Entity\Activation;
use Admin\Entity\User;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


class ActivationController extends AbstractActionController
{
    protected $em;

    protected $lastError;

    public function getEntityManager()
    {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        }
        return $this->em;
    }

    protected function updateUserStateToActivated($userId)
    {
        try
        {
            $user = $this->getEntityManager()->find(User::class, $userId);

            if ($user === null) {
                throw new \Exception("Пользователь не найден. Обратитесь в службу поддержки.");
            }

            if ($config = $this->getServiceLocator()->get('Config'))
            {
                if (
                    ! isset($config['zfcuser']['allowed_login_states'])
                    || ! is_array($config['zfcuser']['allowed_login_states'])
                )
                {
                    throw new \Exception("Активация логина отключена. Обратитесь в службу поддержки.");
                }

                $state = $config['zfcuser']['allowed_login_states'][0];

                $user->setState($state);
                $this->getEntityManager()->flush();

                return true;
            }
        } catch (\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }

        return false;
    }

    protected function activeUser()
    {
        $this->getEntityManager()->getConnection()->beginTransaction();
        try
        {
            $token = $this->getEvent()->getRouteMatch()->getParam('token');

            if ($activationRecord =
                $this->getEntityManager()->getRepository(Activation::class)->findOneBy(['token' => $token])
            )
            {
                if ($userId = $activationRecord->getUserId())
                {
                    if ( ! $this->updateUserStateToActivated($userId))
                    {
                        throw new \Exception("Ошибка активации. Обратитесь в службу поддержки.");
                    }

                    $this->getEntityManager()->remove($activationRecord);
                    $this->getEntityManager()->flush();
                    $this->getEntityManager()->getConnection()->commit();
                    return true;
                }
            }
            else
            {
                throw new \Exception("Аккаунт уже активирован. Обратитесь в службу поддержки.");
            }

        } catch (\Exception $e)
        {
            $this->getEntityManager()->getConnection()->rollBack();
            $this->lastError = $e->getMessage();
        }

        return false;
    }

    public function indexAction()
    {
        $success = $this->activeUser();

        $view = new ViewModel(array(
            'message' => $this->lastError,
            'success' => $success,
            'loginLink' => $this->url()->fromRoute("zfcuser/login")
        ));
        $view->setTemplate('admin/index/index');
        return $view;
    }
}