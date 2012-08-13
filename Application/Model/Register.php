<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace Application\Model;

class Register extends \JackAssPHP\Core\DataModel
{

    protected $table_name = "register";

    public function add ( $username, $name, $email, $password, $key )
    {
        $statement = $this->getStatement(
            "INSERT INTO `$this->table_name`
            (`UserName`, `Name`, `Email`, `Password`, `Key`, `Time`)
            VALUES (:username, :name, :email, :password, :key, :time);"
        );
        $statement->bindValue(":username", $username);
        $statement->bindValue(":name", $name);
        $statement->bindValue(":email", $email);
        $statement->bindValue(":password", $password);
        $statement->bindValue(":key", $key);
        $statement->bindValue(":time", time());

        $result = $statement->execute();
        return $result;
    }

    /**
     * Delete old data.
     */
    public function dumpOld ()
    {
        $statement = $this->getStatement(
            "DELETE FROM `$this->table_name` WHERE `Time` < :time;"
        );

        $statement->bindValue(":time", time() - 60 * 60 * 24 );
        $statement->execute();
    }

    public function keyExists( $key )
    {
        $statement = $this->getStatement(
            "SELECT * FROM `$this->table_name` WHERE `Key` = :key;"
        );
        $statement->bindValue(":key", $key);
        $statement->execute();
        $result = $statement->fetchAll();

        if ( count($result) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function moveToUser ( $key )
    {
        $statement = $this->getStatement(
        "INSERT INTO `user` (UserName, Name,Password,Email)
            SELECT UserName, Name, Password, Email FROM
            `$this->table_name` WHERE `Key` = :key LIMIT 1;"
        );

        $statement->bindValue(":key", $key);

        if ( $statement->execute() ) {
            $this->deleteKey($key);
        } else {
            return false;
        }
    }

    public function deleteKey ( $key )
    {
        $statement = $this->getStatement(
            "DELETE FROM `$this->table_name` WHERE `Key` = :key LIMIT 1;"
        );

        $statement->bindValue(":key", $key);

        return $statement->execute();
    }
}