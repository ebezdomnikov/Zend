<?php

namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;

/**
 * A music album.
 *
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @property string $firstname
 * @property string $lastname
 * @property int $id
 */
class User extends \ZfcUserDoctrineORM\Entity\User
{
    /** @Column(type="string") **/
    protected $firstname;

    /** @Column(type="string") **/
    protected $lastname;


    public function getDisplayName()
    {
        return $this->getLastname() . " " . $this->getFirstname();
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     * @return User
     */
    public function setFirstname( $firstname )
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     * @return User
     */
    public function setLastname( $lastname )
    {
        $this->lastname = $lastname;

        return $this;
    }
}
