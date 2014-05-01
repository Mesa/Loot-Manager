<?php

namespace App\Entity;

/**
 * @Entity
 * @Table(name="blacklist")
 */
class BlackList
{

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;
    /**
     * @Column(type="string", length=24)
     */
    protected $ip;

}
