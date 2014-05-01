<?php


namespace JackAssPHP\Core;

class DataModel
{
    protected $statement = array();
    /**
     * @var [Object] $database DB handle
     */
    protected $database = null;

    protected function getStatement($statement)
    {
        $name = md5($statement);
        if (!isset($this->statement[$name])) {
            $this->statement[$name] = $this->database->prepare($statement);
        }

        return $this->statement[$name];
    }
}