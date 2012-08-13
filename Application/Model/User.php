<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace Application\Model;

class User extends \JackAssPHP\Core\DataModel
{

    protected $database = null;
    protected $user_list = null;
    protected $table_name = "user";
    protected $guest_acc_id = 0;

    public function __construct ()
    {
        $this->database = \Factory::getDB();
    }

    public function getUserList ()
    {
        if (count($this->user_list) == 0) {
            $request = $this->database->prepare("SELECT * FROM `$this->table_name` ORDER BY `Name` ASC");
            $request->execute();
            $this->user_list = $request->fetchAll();
        }
        return $this->user_list;
    }

    public function getUserByToken ( $token )
    {
        $request = $this->database->prepare(
                "SELECT `Id`, `Name`, `UserName` FROM `$this->table_name`
                WHERE `token` = :token LIMIT 1"
        );

        $request->bindParam(":token", $token, \PDO::PARAM_STR);
        $request->execute();

        return $request->fetch();
    }

    /**
     * Create User in UserDB
     *
     * @param [String] $username User Login Name
     * @param [String] $password Password
     * @param [String] $email User Email Adress
     * @param [String] $description User Description
     * @param [Strnig] $displayname Name to Display in the System
     *
     * @return [Void]
     */
    public function createUser ( $username, $password, $email, $name, $status )
    {
        if (strlen($username) > 3 and strlen($name) > 2) {

            $request = $this->database->prepare(
                    "INSERT INTO `$this->table_name`
                (`UserName`, `Password`, `Name`, `Email`, `status`, `registered_since`)
                VALUES
                (:username, :password, :name, :email, :status, :date)");
            $request->bindParam(":username", $username, \PDO::PARAM_STR);
            $request->bindParam(":password", $password, \PDO::PARAM_STR);
            $request->bindParam(":email", $email, \PDO::PARAM_STR);
            $request->bindParam(":name", $name, \PDO::PARAM_STR);
            $request->bindParam(":status", $status, \PDO::PARAM_INT);
            $request->bindParam(":date", time());
            $request->execute();
        } else {
            return false;
        }
    }

    public function deleteUser ( $user_id )
    {
        $request = $this->database->prepare(
                "DELETE FROM `$this->table_name` WHERE `Id` = :user_id LIMIT 1;");
        $request->bindParam(":user_id", $user_id, \PDO::PARAM_INT);
        $request->execute();
    }

    /**
     * Edit user in DB
     *
     * @param type $user_id Unique user number
     * @param type $username Login Username
     * @param type $shown_name The shown name
     * @param type $password password
     * @param type $email email
     *
     * @return type PDO request
     */
    public function editUser ( $user_id, $username, $shown_name, $password, $email )
    {
        if ($password === false) {
            $request = $this->database->prepare(
                    "UPDATE `$this->table_name` SET
            `UserName` = :username,
            `Name` = :name,
            `Email` = :email
            WHERE `Id`= :user_id;");
            $request->bindParam(":username", $username, \PDO::PARAM_STR);
            $request->bindParam(":email", $email, \PDO::PARAM_STR);
            $request->bindParam(":name", $shown_name, \PDO::PARAM_STR);
            $request->bindParam(":user_id", $user_id, \PDO::PARAM_INT);

            try {
                $request->execute();
            } catch (\PDOException $exc) {
                echo $exc->getMessage();
            }
        } else {

            $request = $this->getStatement(
                    "UPDATE `$this->table_name` SET
            `UserName` = :username,
            `Password` = :password,
            `Name` = :name,
            `Email` = :email
            WHERE `Id`= :user_id;");
            $request->bindParam(":username", $username, \PDO::PARAM_STR);
            $request->bindParam(":password", $password, \PDO::PARAM_STR);
            $request->bindParam(":email", $email, \PDO::PARAM_STR);
            $request->bindParam(":name", $shown_name, \PDO::PARAM_STR);
            $request->bindParam(":user_id", $user_id, \PDO::PARAM_INT);

            $request->execute();
        }
    }

    public function getUserNameById ( $id )
    {
        $request = $this->getStatement("SELECT `Name` FROM `$this->table_name` WHERE `Id` = :id LIMIT 1");
        $request->bindValue(":id", $id, \PDO::PARAM_INT);
        $request->execute();

        $result = $request->fetch();
        return $result["Name"];
    }

    public function setLoginName ( $id, $login_name )
    {
        $request = $this->getStatement("UPDATE `$this->table_name` SET `UserName` = :name WHERE `Id` = :id LIMIT 1");
        $request->bindValue(":id", $id, \PDO::PARAM_INT);
        $request->bindValue(":name", $login_name);
        return $request->execute();
    }

    public function setPassword ( $id, $password )
    {
        $request = $this->getStatement("UPDATE `$this->table_name` SET `Password` = :password WHERE `Id` = :id LIMIT 1");
        $request->bindValue(":id", $id, \PDO::PARAM_INT);
        $request->bindValue(":password", $password);
        return $request->execute();
    }

    public function setName ( $id, $name )
    {
        $request = $this->getStatement("UPDATE `$this->table_name` SET `Name` = :name WHERE `Id` = :id;");
        $request->bindValue(":name", $name);
        $request->bindValue(":id", $id, \PDO::PARAM_INT);
        return $request->execute();
    }

    public function setEmail ( $id, $email )
    {
        $request = $this->getStatement("UPDATE `$this->table_name` SET `Email` = :email WHERE `Id` = :id;");
        $request->bindValue(":id", $id);
        $request->bindValue(":email", $email);
        return $request->execute();
    }

    public function setUserToken ( $user_id, $token )
    {
        $request = $this->database->prepare(
                "UPDATE `$this->table_name` SET `token`=:token WHERE `Id`= :user_id LIMIT 1;"
        );
        $request->bindParam(":token", $token, \PDO::PARAM_STR);
        $request->bindParam(":user_id", $user_id, \PDO::PARAM_INT);

        $request->execute();
    }

    public function setLastLogin ( $user_id, $timestamp )
    {
        $request = $this->database->prepare(
                "UPDATE `$this->table_name` SET `lastLogin` = :timestamp
                WHERE `Id` = :user_id LIMIT 1;"
        );

        $request->bindParam(":timestamp", $timestamp, \PDO::PARAM_INT);
        $request->bindParam(":user_id", $user_id, \PDO::PARAM_INT);
        $request->execute();
    }

    public function getUserById ( $id )
    {
        $request = $this->database->prepare("SELECT * FROM `$this->table_name` WHERE `Id` = :id LIMIT 1");
        $request->bindValue(":id", $id, \PDO::PARAM_INT);
        $request->execute();

        return $request->fetch();
    }

    public function getGuestAccount ()
    {
        $request = $this->database->prepare("SELECT * FROM `$this->table_name` WHERE `Name` = 'Guest' LIMIT 1;");
        $request->execute();

        return $request->fetch();
    }

    public function isGuestAccount ( $id )
    {
        $user_id = (int) $id;
        if ($this->guest_acc_id == 0) {
            $guest_acc_data = $this->getGuestAccount();
            $this->guest_acc_id = $guest_acc_data["Id"];
        }

        if ($user_id == $this->guest_acc_id) {
            return true;
        } else {
            return false;
        }
    }

    public function getUserByNamePassword ( $username, $password )
    {
        $request = $this->database->prepare(
                "SELECT * FROM `$this->table_name`
                WHERE
                `UserName` = :username
                and
                `Password` = :password"
        );

        $request->bindValue(':username', $username, \PDO::PARAM_STR);
        $request->bindValue(':password', $password, \PDO::PARAM_STR);
        $request->execute();

        return $request->fetch();
    }

    /**
     * Check for existent Username (Registration helper)
     *
     * @param [String] $name
     */
    public function usernameExists ( $name )
    {

        $encrypted_name = base64_encode($name);
        $statement = $this->getStatement(
                "SELECT `UserName` FROM `$this->table_name`
                WHERE `UserName` = :name LIMIT 1"
        );
        $statement->bindValue(":name", $encrypted_name);
        $statement->execute();
        return $statement->fetch();
    }

    public function nameExists ( $name )
    {
        $request = $this->getStatement("SELECT count(*) FROM `$this->table_name` WHERE `Name` = :name");
        $request->bindValue(":name", $name);
        $request->execute();
        $count = $request->fetch();

        if ($count["count(*)"] > 0 ) {
            return true;
        } else {
            return false;
        }
    }

    public function emailExists ( $email )
    {
        $statement = $this->getStatement("SELECT count(*) FROM `$this->table_name` WHERE `Email` = :email");
        $statement->bindValue(":email", $email);
        $statement->execute();
        $count = $statement->fetch();
        return (int) $count["count(*)"];
    }
}

?>
