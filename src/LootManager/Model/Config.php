<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace Application\Model;

class Config extends \JackAssPHP\Core\DataModel
{

    protected $table_name = "config";

    public function getAllData ()
    {
        $statement = $this->getStatement("SELECT * FROM `$this->table_name`");
        $statement->execute();
        return $statement->fetchAll();
    }

    public function insertValue ( $name, $value )
    {
        $statement = $this->getStatement("INSERT INTO `$this->table_name` (`Name`, `Value`) VALUES (:name, :value)");
        $statement->bindValue(":name", $name);
        $statement->bindValue(":value", $value);
        $statement->execute();
    }

    public function updateValue ( $name, $value )
    {
        $statement = $this->getStatement("UPDATE `$this->table_name` SET `Value` = :value WHERE `Name` = :name");
        $statement->bindValue(":name", $name, \PDO::PARAM_STR);
        $statement->bindValue(":value", $value);
        $statement->execute();
    }

    public function rmValue ( $name )
    {
        $statement = $this->getStatement("DELETE FROM `$this->table_name` WHERE `Name` = :name LIMIT 1");
        $statement->bindValue(":name", $name);
        $statement->execute();
    }

    public function getValue ( $name )
    {
        $statement = $this->getStatement("SELECT `Value` FROM `$this->table_name` WHERE `Name` = :name");
        $statement->bindParam(":name", $name);
        $statement->execute();
        $data = $statement->fetch();
        return $data["Value"];
    }
}