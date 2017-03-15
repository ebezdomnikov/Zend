<?php

namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;


/**
 * User activation table.
 *
 * @ORM\Entity
 * @ORM\Table(name="user_activation")
 */
class Activation
{
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /** @Column(type="integer") **/
    protected $user_id;

    /** @Column(type="string") **/
    protected $token;

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     * @return Activation
     */
    public function setUserId( $user_id )
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     * @return Activation
     */
    public function setToken( $token )
    {
        $this->token = $token;

        return $this;
    }

}