<?php

namespace Admin\Form;

use Zend\Form\Element\Captcha as Captcha;
use ZfcUser\Options\RegistrationOptionsInterface;

class Register extends \ZfcUser\Form\Register
{
    /**
     * @param string|null $name
     * @param RegistrationOptionsInterface $options
     */
    public function __construct($name, RegistrationOptionsInterface $options)
    {
        parent::__construct($name, $options);

        $this->add(array(
            'name' => 'firstname',
            'options' => array(
                'label' => 'Имя',
            ),
            'attributes' => array(
                'type' => 'text'
            ),
        ),
        array(
            'priority' => 1,
        ));

        $this->add(array(
            'name' => 'lastname',
            'options' => array(
                'label' => 'Фамилия',
                'priority' => 2
            ),
            'attributes' => array(
                'type' => 'text'
            ),
        ),
        array(
            'priority' => 2,
        ));

    }
}
