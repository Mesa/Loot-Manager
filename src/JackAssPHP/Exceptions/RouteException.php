<?php

/**
 * Mesas Loot Manager
 * Copyright (C) 2011  Mesa <Daniel Langemann>
 *
 * Php version 5.3
 *
 * @category Exception
 * @package  Mesas_Loot_Manager
 * @author   Mesa <daniel.langemann@gmx.de>
 */

namespace JackAssPHP\Exceptions;

class RouteException extends \Exception
{

    public function __construct ( $msg ) {
         parent::__construct();
         $this->message = $msg;
    }
}