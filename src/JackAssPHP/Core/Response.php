<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace JackAssPHP\Core;

class Response
{
    protected $data = array();

    public function __construct () {

    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __get($name)
    {
        if ( isset($this->data[$name]) ) {
            return $this->data[$name];
        } else {
            return "";
        }
    }
}
