<?php

namespace Commander\Annotation;

/**
 * @Annotation
 */
class Role
{
    const read   = 1;
    const edit   = 3;
    const delete = 7;

    const admin = "admin";
    const guest = "guest";

    public $name = false;
    public $mode = false;
}
