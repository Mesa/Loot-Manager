<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace JackAssPHP\Core;

class ResponseJson extends Response
{
    protected $data = array();

    public function __construct () {

    }

    public function __destruct()
    {
        echo json_encode($this->data);
    }
}
