<?php

namespace Admin\Controller;

use Admin\Entity\User;
use Zend\Form\FormInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\Stdlib\Parameters;
use Zend\View\Model\ViewModel;
use ZfcUser\Service\User as UserService;
use ZfcUser\Options\UserControllerOptionsInterface;

class UserController extends \ZfcUser\Controller\UserController
{
    protected $em;

    protected $indexForm;

    public function getEntityManager()
    {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        }
        return $this->em;
    }


    public function setIndexForm(FormInterface $indexForm)
    {
        $this->indexForm = $indexForm;
        $fm = $this->flashMessenger()->setNamespace('zfcuser-index-form')->getMessages();
        if (isset($fm[0])) {
            $this->indexForm->setMessages(
                array('identity' => array($fm[0]))
            );
        }
        return $this;
    }


    public function getIndexForm()
    {
        if (!$this->indexForm) {
            $this->setIndexForm($this->serviceLocator->get('zfcuser_index_form'));
        }
        return $this->indexForm;
    }
    /**
     * User page
     */
    public function indexAction()
    {
        if (!$this->zfcUserAuthentication()->hasIdentity()) {
            return $this->redirect()->toRoute(static::ROUTE_LOGIN);
        }

        $request = $this->getRequest();
        $form    = $this->getIndexForm();

        $redirect = false;

        if (! $request->isPost())
        {
            $userAuth = $this->zfcUserAuthentication();

            if ($userAuth->hasIdentity())
            {
                $form->setData(
                    [
                        'firstname'=> $userAuth->getIdentity()->getFirstname(),
                        'lastname'=> $userAuth->getIdentity()->getLastname(),
                        'userId' => $userAuth->getIdentity()->getId(),
                    ]
                );
            }


            $view = new ViewModel(array(
                'indexForm' => $form,
                'redirect' => $redirect,
            ));
            $view->setTemplate('zfc-user/user/index');
            return $view;
        }

        $form->setData($request->getPost());

        if (!$form->isValid()) {
            $this->flashMessenger()->setNamespace('zfcuser-index-form')->addMessage($this->failedLoginMessage);
            return $this->redirect()->toUrl('/user');
        }

        if ($this->zfcUserAuthentication()->hasIdentity())
        {
            $userId = $this->zfcUserAuthentication()->getIdentity()->getId();

            $user = $this->getEntityManager()->find(User::class, $userId);

            $user->setFirstname($request->getPost("firstname"));
            $user->setLastname($request->getPost("lastname"));
            $this->getEntityManager()->flush();
        }

        $view = new ViewModel(array(
            'indexForm' => $form,
            'redirect' => $redirect,
        ));

        return $view;//new ViewModel();
    }
}
