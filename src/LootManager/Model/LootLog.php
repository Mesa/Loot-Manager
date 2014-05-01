<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace Application\Model;

class LootLog
{

    protected $table_name = 'loot_log';
    protected $database = null;

    public function __construct ()
    {
        $this->database = \Factory::getDB();
    }

    public function addEntry (
    $EventId, $CharId, $FromPosition, $ToPosition, $AdminId, $Desc, $Type = ""
    )
    {
        $request = $this->database->prepare(
                "INSERT INTO `$this->table_name` " .
                "(EventId, CharId, FromPosition, ToPosition, `Time`, AdminId, `Desc`, `Type`) " .
                "VALUES (:EventId, :CharId, :FromPosition, :ToPosition, :Time, :AdminId, :Desc, :Type)");
        $request->bindParam(":EventId", $EventId, \PDO::PARAM_INT);
        $request->bindParam(":CharId", $CharId, \PDO::PARAM_INT);
        $request->bindParam(":FromPosition", $FromPosition, \PDO::PARAM_INT);
        $request->bindParam(":ToPosition", $ToPosition, \PDO::PARAM_INT);
        $request->bindParam(":Time", time(), \PDO::PARAM_INT);
        $request->bindParam(":AdminId", $AdminId, \PDO::PARAM_INT);
        $request->bindParam(":Desc", $Desc, \PDO::PARAM_INT);
        $request->bindParam(":Type", $Type, \PDO::PARAM_INT);

        $result = $request->execute();
    }

    public function getDescList ( $param = null )
    {
        if ( $param == null ) {
            $request = $this->database->prepare("SELECT `Desc` FROM `$this->table_name` WHERE `Desc` IS NOT NULL and `DESC` != '' GROUP BY `Desc` ASC;");
        } else {
            $request = $this->database->prepare("SELECT `Desc` FROM `$this->table_name` WHERE `Type` = :param GROUP BY `Desc` ASC;");
            $request->bindParam(":param", $param);
        }

        $request->execute();

        $data = $request->fetchAll();

        foreach ( $data as $key => $value ) {
            $list[] = $value["Desc"];
        }

        return $list;
    }

    public function getLastLogLines ( $limit = 15 )
    {
        $request = $this->database->prepare("SELECT * FROM `$this->table_name` ORDER BY `Id` DESC LIMIT $limit");
        $request->execute();
        return $request->fetchAll();
    }

    public function getCharLog ( $charId, $eventId = 0 , $args = "",  $limit = 25)
    {
        if ( count($args) !== 4 ) {
            $args["suicide"] = "";
            $args["moved"] = "";
            $args["added"] = "";
            $args["removed"] = "";
        }
        if ( $eventId != 0) {
            $request = $this->database->prepare("SELECT * FROM `$this->table_name` WHERE `CharId` = :CharId and `EventId` = :EventId and ( `Type` = :suicide or `Type` = :moved or `Type` = :removed or `Type` = :added ) ORDER BY `Id` DESC Limit :limit");
            $request->bindParam(":EventId", $eventId);
        } else {
            $request = $this->database->prepare("SELECT * FROM `$this->table_name` WHERE `CharId` = :CharId and ( `Type` = :suicide or `Type` = :moved or `Type` = :removed or `Type` = :added ) ORDER BY `Id` DESC Limit :limit");
        }

        $request->bindParam(":moved", $args["moved"]);
        $request->bindParam(":suicide", $args["suicide"]);
        $request->bindParam(":added", $args["added"]);
        $request->bindParam(":removed", $args["removed"]);
        $request->bindParam(":limit", $limit, \PDO::PARAM_INT);
        $request->bindParam(":CharId", $charId, \PDO::PARAM_INT);
        $request->execute();

        return $request->fetchAll();
    }

    public function getEventLog ( $eventId, $args = null, $limit = 50 )
    {
        if ( count($args) !== 4 ) {
            $args["suicide"] = "";
            $args["moved"] = "";
            $args["added"] = "";
            $args["removed"] = "";
        }

        $request = $this->database->prepare("SELECT * FROM `$this->table_name` WHERE `EventId` = :EventId and ( `Type` = :suicide or `Type` = :moved or `Type` = :removed or `Type` = :added ) ORDER BY `Id` DESC LIMIT :limit");
        $request->bindParam(":suicide", $args["suicide"]);
        $request->bindParam(":moved", $args["moved"]);
        $request->bindParam(":added", $args["added"]);
        $request->bindParam(":removed", $args["removed"]);
        $request->bindParam(":limit", $limit, \PDO::PARAM_INT);
        $request->bindParam(":EventId", $eventId, \PDO::PARAM_INT);
        $request->execute();

        return $request->fetchAll();
    }

    public function getItemCount ( $CharId, $EventId )
    {
        $char_id = (int) $CharId;
        $event_id = (int) $EventId;

        if ( $event_id == 0 ) {
            $this->itemCountRequest = $this->database->prepare("SELECT count(*) count FROM `$this->table_name` WHERE `CharId` = :player_id AND `Type` = 'suicide'");
        } else {
            $this->itemCountRequest = $this->database->prepare("SELECT count(*) count FROM `$this->table_name` WHERE `CharId` = :player_id AND `EventId` = :event_id AND `Type` = 'suicide'");
            $this->itemCountRequest->bindValue(":event_id", $event_id);
        }

        $this->itemCountRequest->bindValue(":player_id", $char_id);
        $this->itemCountRequest->execute();

        $data = $this->itemCountRequest->fetch();
        return $data["count"];
    }

    public function getEventJoinDate ( $CharId, $EventId )
    {

        $char_id = (int) $CharId;
        $event_id = (int) $EventId;


        if ( $event_id == 0 ) {
            $this->charJoinDateRequest = $this->database->prepare("SELECT `Time` FROM `$this->table_name` WHERE `CharId` = :char_id AND `Type` = 'char_added_to_event' ORDER BY `Id` ASC LIMIT 1");
        } else {
            $this->charJoinDateRequest = $this->database->prepare("SELECT `Time` FROM `$this->table_name` WHERE `CharId` = :char_id AND `EventId` = :event_id AND `Type` = 'char_added_to_event' ORDER BY `Id` DESC LIMIT 1");
            $this->charJoinDateRequest->bindValue(":event_id", $event_id);
        }

        $this->charJoinDateRequest->bindValue(":char_id", $char_id);
        $this->charJoinDateRequest->execute();

        $data = $this->charJoinDateRequest->fetch();
        return $data["Time"];
    }


}
