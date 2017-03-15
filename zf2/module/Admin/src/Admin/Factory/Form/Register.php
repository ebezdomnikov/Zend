<?php
/**
 * Created by PhpStorm.
 * User: Clayton Daley
 * Date: 3/10/2015
 * Time: 9:34 AM
 */

namespace Admin\Factory\Form;

use Zend\ServiceManager\ServiceLocatorInterface;
use Admin\Form\RegisterFilter;
use ZfcUser\Validator;

class Register extends \ZfcUser\Factory\Form\Register
{
    public function createService(ServiceLocatorInterface $formElementManager)
    {
        if ($formElementManager instanceof FormElementManager) {
            $sm = $formElementManager->getServiceLocator();
            $fem = $formElementManager;
        } else {
            $sm = $formElementManager;
            $fem = $sm->get('FormElementManager');
        }

        $options = $sm->get('zfcuser_module_options');

        $form = new \Admin\Form\Register(null, $options);
        // Inject the FormElementManager to support custom FormElements
        $form->getFormFactory()->setFormElementManager($fem);

        $form->setHydrator($sm->get('zfcuser_register_form_hydrator'));

        $form->setInputFilter(new RegisterFilter(
            new Validator\NoRecordExists(array(
                'mapper' => $sm->get('zfcuser_user_mapper'),
                'key'    => 'email'
            )),
            new Validator\NoRecordExists(array(
                'mapper' => $sm->get('zfcuser_user_mapper'),
                'key'    => 'username'
            )),
            $options
        ));

        return $form;
    }
}
