<?php

namespace JackAssPHP\Logger;

class DbLogger implements \JackAssPHP\Logger\LoggerInterface
{
    protected $database   = null;
    protected $table_name = null;
    protected $statement  = null;
    protected $message    = null;

    public function __construct ( \PDO $database, $table_name)
    {
        $this->database   = $database;
        $this->table_name = $table_name;
        $this->statement  = $this->database->prepare("INSERT INTO `$this->table_name` (`Message`, `Time`) VALUES (:message, :time)");
        $this->statement->bindParam(":message", $this->message);
        $this->statement->bindParam(":time", $this->time);
    }

    public function log ( $msg )
    {
        $this->message = $msg;
        $this->time    = time();
        $this->statement->execute();
    }
}