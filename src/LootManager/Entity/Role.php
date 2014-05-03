<?php
/**
 * Created by PhpStorm.
 * User: mesa
 * Date: 03.05.14
 * Time: 18:44
 */

namespace LootManager\Entity;


/**
 * @Entity
 */
class Role
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;
    /**
     * @Column(length=255, unique=true)
     */
    protected $name;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}

