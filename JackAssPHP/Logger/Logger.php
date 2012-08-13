<?php

namespace \JackAssPHP\Logger;

class Logger
{

    public function setDbConnection ( \PDO $database )
    {
        $this->database = $database;
    }
}