<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace Application\Model;

class Chars extends \JackAssPHP\Core\DataModel
{

    protected $registry = null;
    protected $sql_player_list = null;
    protected $event_list = null;
    protected $player_list = array();
    protected $table_name = "chars";

    public function __construct ()
    {
        parent::__construct();
    }

    public function getMemberWithoutGuild ()
    {
        $request = $this->getStatement("SELECT * FROM `$this->table_name` WHERE `GuildRankId` IS NULL ORDER BY `Name` ASC");
        $request->execute();

        return $request->fetchAll();
    }

    public function getAllCharsByRank ( $rankId )
    {
        $request = $this->getStatement("SELECT * FROM `$this->table_name` WHERE `GuildRankId` = :rank ORDER BY `Name` ASC");
        $request->bindValue(":rank", $rankId, \PDO::PARAM_INT);
        $request->execute();

        return $request->fetchAll();
    }

    public function getClassCount ()
    {
        $request = $this->getStatement("SELECT count(*) count, `class` name FROM `chars` GROUP BY `Class`");
        $request->execute();

        return $request->fetchAll();
    }

    public function getCharCount ()
    {
        $request = $this->getStatement("SELECT count(*) count FROM `chars`");
        $request->execute();

        $data = $request->fetchAll();
        return (int) $data[0]["count"];
    }

    public function getPlayerList ( $event_id )
    {
        if ( !isset($this->player_list[$event_id]) or count($this->player_list[$event_id]) == 0 ) {
            $this->createPlayerList($event_id);
        }
        return $this->player_list[$event_id];
    }

    public function getEventList ()
    {
        if ( $this->event_list == null ) {
            $this->createEventList();
        }
        return $this->event_list;
    }

    protected function createPlayerList ( $event_id = 0 )
    {
        $id = (int) $event_id;
        $request = $this->getStatement("SELECT `lootorder`.`Id`, `lootorder`.`LastLoot`, `lootorder`.`CharId`,`lootorder`.`Position`,`chars`.`Name`,`chars`.`Class`,`chars`.`Description`  FROM `lootorder` LEFT JOIN `chars` ON `lootorder`.`charId` = `chars`.`Id` WHERE `lootorder`.`EventId` = :event_id ORDER BY `lootorder`.`Position` ASC");
        $request->bindParam(":event_id", $id, \PDO::PARAM_INT);
        $request->execute();
        $this->player_list[$id] = $request->fetchAll();
    }

    public function getCharName ( $char_id )
    {
        $statement = $this->getStatement("SELECT `Name` FROM `chars` WHERE `Id` = :char_id LIMIT 1");
        $statement->bindParam(":char_id", $char_id, \PDO::PARAM_INT);
        $statement->execute();
        $result = $statement->fetch();

        return $result["Name"];
    }

    protected function createEventList ()
    {
        $request = $this->getStatement("SELECT * FROM `events`");
        $request->execute();

        $result = $request->fetchAll();

        foreach ( $result as $event ) {
            $this->event_list[$event["Id"]] = $event;
        }
    }

    /**
     * @todo wtf move it to event DAO, i dont know why it is here?
     */
    public function getEventName ( $event_id )
    {
        if ( $this->event_list == null ) {
            $this->createEventList();
        }

        if ( isset($this->event_list[$event_id]) ) {
            return $this->event_list[$event_id]["Name"];
        } else {
            return false;
        }
    }
    /**
     * @todo the same again?? move to Event DAO
     */
    public function eventExists ( $event_id )
    {
        $request = $this->getStatement("SELECT * FROM `events` WHERE `Id` = :event_id LIMIT 1");
        $request->bindParam(":event_id", $event_id, \PDO::PARAM_INT);
        $request->execute();

        if ( $request->fetch() === false ) {
            return false;
        } else {
            return true;
        }
    }

    public function playerNotInEvent ( $event_id )
    {
        $request = $this->getStatement("SELECT * FROM `chars` WHERE `chars`.`Id` not in (SELECT `lootorder`.`CharId` FROM `lootorder` WHERE `lootorder`.`EventId`= :event_id) ORDER BY `chars`.`Name` ASC");
        $request->bindParam(":event_id", $event_id, \PDO::PARAM_INT);
        $request->execute();

        return $request->fetchAll();
    }

    protected function recountPlayer ( $event_id )
    {
        if ( !isset($this->player_list[$event_id]) ) {
            $this->getPlayerList($event_id);
        }

        $position = (int) 1;
        $id = (int) 0;

        $request = $this->getStatement("
            UPDATE `lootorder`
            SET `Position` = :position
            WHERE `Id` = :id");

        foreach ( $this->player_list[$event_id] as $player ) {
            $request->bindValue(":id", $player["Id"], \PDO::PARAM_INT);
            $request->bindValue(":position", $position, \PDO::PARAM_INT);
            $request->execute();
            $position++;
        }
    }

    public function movePlayerToBottom ( $event_id, $player_id )
    {
        if ( !isset($this->player_list[$event_id]) ) {
            $this->getPlayerList($event_id);
        }
        $request = $this->getStatement("UPDATE `lootorder` SET `LastLoot`=:timestamp WHERE `Id`=:user_id;");
        $request->bindParam(":timestamp", time(), \PDO::PARAM_INT);
        $request->bindParam(":user_id", $player_id, \PDO::PARAM_INT);
        $request->execute();

        foreach ( $this->player_list[$event_id] as $player ) {

            if ( $player["Id"] != $player_id ) {
                $data[] = $player;
            } else {
                $last = $player;
            }
        }
        $data[] = $last;

        $this->player_list[$event_id] = $data;
        $this->recountPlayer($event_id);
    }

    public function movePlayerToPosition ( $player_id, $event_id, $new_position )
    {
        if ( !isset($this->player_list[$event_id]) ) {
            $this->getPlayerList($event_id);
        }

        $player_count = count($this->player_list[$event_id]);

        if ( $player_count < $new_position ) {
            /**
             * wenn die angegebene Platzierung größer ist als die Anzahl
             * der Charaktere in der Liste, dann auf den letzten Platz verschieben
             */
            $new_position = $player_count;
            $request = $this->getStatement("UPDATE `lootorder` SET `LastLoot`=:timestamp WHERE `CharId`=:user_id and `EventId` = :event_id;");
            $request->bindParam(":timestamp", time(), \PDO::PARAM_INT);
            $request->bindParam(":user_id", $player_id, \PDO::PARAM_INT);
            $request->bindParam(":event_id", $event_id, \PDO::PARAM_INT);
            $request->execute();
        }
        $position = 0;
        foreach ( $this->player_list[$event_id] as $player ) {

            if ( $player_id == $player["CharId"] ) {
                $moved_player = $player;
                continue;
            }

            if ( $new_position - 1 == $position ) {
                /**
                 * Schonmal den neuen Platz reservieren.
                 */
                $new_list[] = $position;
                $new_list[] = $player;
            } else {
                $new_list[] = $player;
            }
            $position++;
        }

        if ( isset($moved_player) ) {
            $new_list[$new_position - 1] = $moved_player;
            $this->player_list[$event_id] = $new_list;
            $this->recountPlayer($event_id);
        }
    }

    public function addPlayerToEvent ( $char_id, $event_id )
    {
        $request = $this->getStatement("SELECT * FROM `lootorder` WHERE `EventId` = :event_id and `CharId` = :player_id;");
        $request->bindParam(":player_id", $char_id, \PDO::PARAM_INT);
        $request->bindParam(":event_id", $event_id, \PDO::PARAM_INT);
        $request->execute();

        if ( 0 == count($request->fetchAll()) ) {
            unset($request);

            $request = $this->getStatement("SELECT `Position` FROM `lootorder` WHERE `EventId` = :event_id ORDER BY `Position` DESC LIMIT 1;");
            $request->bindParam(":event_id", $event_id, \PDO::PARAM_INT);
            $request->execute();

            $result = $request->fetch();
            $count = $result["Position"] + 1;

            $request = $this->getStatement("INSERT INTO `lootorder` (`EventId`, `CharId`, `Position`) VALUES (:event_id, :char_id, :position);");
            $request->bindParam(":event_id", $event_id, \PDO::PARAM_INT);
            $request->bindParam(":char_id", $char_id, \PDO::PARAM_INT);
            $request->bindParam(":position", $count);
            $request->execute();
        }
    }

    public function getPlayerIdByRow ( $row_id )
    {
        $request = $this->getStatement("SELECT * FROM `lootorder` WHERE `Id` = :row_id LIMIT 1;");
        $request->bindParam(":row_id", $row_id, \PDO::PARAM_INT);
        $request->execute();
        $result = $request->fetch();

        return $result;
    }

    public function dropPlayerfromEvent ( $row_id )
    {
        $request = $this->getStatement("SELECT * FROM `lootorder` WHERE `Id` = :row_id");
        $request->bindValue(":row_id", $row_id);
        $request->execute();
        $result = $request->fetch();

        unset($request);

        $request = $this->getStatement("DELETE FROM `lootorder` WHERE `Id` = :row_id LIMIT 1");
        $request->bindParam(":row_id", $row_id, \PDO::PARAM_INT);
        $request->execute();
        $this->recountPlayer($result["EventId"]);
    }

    public function addPlayer ( $name, $class )
    {
        $request = $this->getStatement("INSERT INTO `chars` (`Name`, `Class`) VALUES (:name, :class);");
        $request->bindParam(":name", $name);
        $request->bindParam(":class", $class);
        $request->execute();
    }

    public function getClassList ()
    {
        $request = $this->getStatement("SELECT `Class` FROM `chars` GROUP BY `Class`;");
        $request->execute();

        $result = $request->fetchAll();

        foreach ( $result as $key => $value ) {
            $list[] = $value["Class"];
        }
        return $list;
    }

    public function editPlayer ( $char_id, $char_name, $char_class, $char_description )
    {
        $request = $this->getStatement("UPDATE `chars` SET `Name`= :char_name, `Class`= :char_class, `Description`= :char_description WHERE `Id`= :char_id;");
        $request->bindParam(":char_name", $char_name);
        $request->bindParam(":char_class", $char_class);
        $request->bindParam(":char_description", $char_description);
        $request->bindParam(":char_id", $char_id, \PDO::PARAM_INT);
        $request->execute();
    }

    public function deletePlayer ( $char_id )
    {
        $request = $this->getStatement("DELETE FROM `chars` WHERE `Id`= :char_id;");
        $request->bindParam(":char_id", $char_id, \PDO::PARAM_INT);
        $request->execute();

        $request = $this->getStatement("DELETE FROM `lootorder` WHERE `CharId`= :char_id;");
        $request->bindParam(":char_id", $char_id, \PDO::PARAM_INT);
        $request->execute();

        foreach ( $this->getEventList() as $key => $value ) {
            $this->recountPlayer($key);
        }
    }

}