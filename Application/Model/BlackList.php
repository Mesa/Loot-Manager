<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace Application\Model;

class BlackList
{

    protected $table_name = "blacklist";
    protected $database = null;

    public function __construct ( $db_connection )
    {
        $this->database = $db_connection;
    }

    public function getListByIp ( $ip )
    {
        $request = $this->database->prepare("SELECT * FROM `$this->table_name` WHERE `ip` = :ip");
        $request->bindValue(":ip", $ip, \PDO::PARAM_INT);
        $request->execute();

        return $request->fetch();
    }

    public function dropOldRows ( $older_then )
    {
        $request = $this->database->prepare("DELETE FROM `$this->table_name` WHERE `locked_until` < :time and `locked_until` > 1;");
        $request->bindValue(":time", $older_then, \PDO::PARAM_INT);
        $request->execute();
    }

    public function dropIp ( $ip )
    {
        $request = $this->database->prepare("DELETE FROM `$this->table_name` WHERE `ip`= :ip;");
        $request->bindParam(":ip", $ip, \PDO::PARAM_INT);
        $request->execute();
    }

    public function addIp ( $ip )
    {
        $request = $this->database->prepare("INSERT INTO `$this->table_name` (`ip`, `login_try`, `last_try`) VALUES (:ip, 1 ,:last_try);");
        $request->bindValue(":ip", $ip, \PDO::PARAM_INT);
        $request->bindValue(":last_try", time(), \PDO::PARAM_INT);
        $request->execute();
    }

    public function updateIp ( $id, $login_try, $locked_until )
    {
        $request = $this->database->prepare("UPDATE `$this->table_name` SET `login_try`= :login_try, `last_try` = :last_try, `locked_until` = :locked_until WHERE `Id`= :row_id;");
        $request->bindValue(":row_id", $id, \PDO::PARAM_INT);
        $request->bindValue(":login_try", $login_try, \PDO::PARAM_INT);
        $request->bindValue(":locked_until", $locked_until, \PDO::PARAM_INT);
        $request->bindValue(":last_try", time(), \PDO::PARAM_INT);
        $request->execute();
    }

}