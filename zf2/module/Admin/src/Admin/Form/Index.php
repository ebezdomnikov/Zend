<?php

namespace Admin\Form;

use ZfcUser\Form\Base;
use ZfcUser\Options\RegistrationOptionsInterface;

class Index extends Base
{
    /**
     * @var RegistrationOptionsInterface
     */
    protected $registrationOptions;

    /**
     * @param string|null $name
     * @param RegistrationOptionsInterface $options
     */
    public function __construct($name, RegistrationOptionsInterface $options)
    {
        $this->setRegistrationOptions($options);
        parent::__construct($name);

        $this->remove('username');
        $this->remove('email');
        $this->remove('display_name');
        $this->remove('password');
        $this->remove('passwordVerify');

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

        $this->get('submit')->setLabel('Обновить');
    }

    /**
     * Set Registration Options
     *
     * @param RegistrationOptionsInterface $registrationOptions
     * @return Register
     */
    public function setRegistrationOptions(RegistrationOptionsInterface $registrationOptions)
    {
        $this->registrationOptions = $registrationOptions;
        return $this;
    }

    /**
     * Get Registration Options
     *
     * @return RegistrationOptionsInterface
     */
    public function getRegistrationOptions()
    {
        return $this->registrationOptions;
    }
}
