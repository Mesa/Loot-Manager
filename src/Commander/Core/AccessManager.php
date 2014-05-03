<?php

namespace Commander\Core;


use Commander\Annotation\Role;
use Commander\Exception\NoAccessException;

class AccessManager
{
    /**
     * @Inject("User")
     */
    protected $user;


    public function check(Role $role)
    {
        if (!$this->user->has($role->name)) {
            throw new NoAccessException($this->user->getName() . "/" .  $role->name);
        }
    }
}