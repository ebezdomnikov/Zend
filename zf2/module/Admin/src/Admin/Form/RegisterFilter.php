<?php

namespace Admin\Form;

use Zend\Validator\StringLength;
use Zend\Validator\ValidatorChain;
use ZfcUser\Options\RegistrationOptionsInterface;
use ZfcUser\Form\RegisterFilter as BaseRegisterFilter;

class RegisterFilter extends BaseRegisterFilter
{
    protected $emailValidator;
    protected $usernameValidator;

    /**
     * @var RegistrationOptionsInterface
     */
    protected $options;

    public function __construct($emailValidator, $usernameValidator, RegistrationOptionsInterface $options)
    {
        parent::__construct($emailValidator, $usernameValidator, $options);

        $this->add(array(
            'name'       => 'firstname',
            'required'   => true,
            'validators' => array(
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'min' => 3,
                        'max' => 255,
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name'       => 'lastname',
            'required'   => true,
            'validators' => array(
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'min' => 3,
                        'max' => 255,
                    ),
                ),
            ),
        ));
    }
}
