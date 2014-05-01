<?php

namespace LootManager\Entity;

/**
 * @Entity
 * @Table(name="user")
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
     * @Column(type="string", length=255)
     */
    protected $name;

}
