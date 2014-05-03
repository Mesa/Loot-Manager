<?php

namespace LootManager\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 */
class User
{

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;
    /**
     * @Column(length=255)
     */
    protected $name;

    /**
     * @Column(length=255)
     */
    protected $password;

    /**
     * @Column(length=255)
     */
    protected $login;

    /**
     * @Column(type="datetime")
     */
    protected $lastAccess;

    /**
     * @Column(type="boolean")
     */
    protected $active;

    /**
     * @Column(length=255, unique=true)
     */
    protected $email;
    /**
     * @ManyToMany(targetEntity="Role")
     */
    protected $roles;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param mixed $roles
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param mixed $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return mixed
     */
    public function getLastAccess()
    {
        return $this->lastAccess;
    }

    /**
     * @param mixed $lastAccess
     */
    public function setLastAccess($lastAccess)
    {
        $this->lastAccess = $lastAccess;
    }

    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param mixed $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }
}
