<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace Application\Model;

class Encounter extends \JackAssPHP\Core\DataModel
{

    protected $table_name = "Encounter";

    /**
     * Create new encounter in DB
     *
     * @param [String] $name encounter name
     *
     * @return [Int] Last insert id
     */
    public function createEncounter ( $name )
    {
        $request = $this->getStatement("INSERT INTO `$this->table_name` (`Name`, `Dungeon`, `Status`) VALUES (:name, '0', '0')");
        $request->bindValue(":name", $name);
        $request->execute();

        /**
         * This method may not return a meaningful or consistent result across
         * different PDO drivers, because the underlying database may not even
         * support the notation of auto-increment fields or sequences.
         *
         * http://php.net/manual/de/pdo.lastinsertid.php
         *
         * So i handle it my self.
         */
        $request = $this->database->prepare("SELECT `Id` FROM `$this->table_name` WHERE `Name` = :name LIMIT 1");
        $request->bindValue(":name", $name);
        $request->execute();

        return $request->fetch();
    }

    /**
     * returns all Encounter sorted by Dungeon $ID and order
     *
     * @return [Array] all Encounter sorted by Dungeon Id and Order
     */
    public function getAllEncounter ()
    {
        $request = $this->database->query("SELECT * FROM `$this->table_name` ORDER BY `Order` ASC");

        $data = array();
        while ($encounter = $request->fetch()) {
            $data[$encounter["Dungeon"]][$encounter["Id"]] = $encounter;
        }

        return $data;
    }

    public function addToDungeon ( $encounter_id, $dungeon_id )
    {

        $last_place = $this->getStatement("SELECT `Order` FROM `$this->table_name` WHERE `Dungeon` = :dungeonId ORDER BY `Order` DESC LIMIT 1");
        $last_place->bindValue(":dungeonId", $dungeon_id, \PDO::PARAM_INT);
        $last_place->execute();

        $new_position = $last_place->fetch();

        $request = $this->database->prepare("UPDATE `$this->table_name` SET `Dungeon`=:dungeonId, `Order` = :newPosition WHERE `Id`=:id");
        $request->bindValue(":id", $encounter_id, \PDO::PARAM_INT);
        $request->bindValue(":dungeonId", $dungeon_id, \PDO::PARAM_INT);
        $request->bindValue(":newPosition", $new_position["Order"] + 1, \PDO::PARAM_INT);
        $request->execute();
    }

    public function getEncounter ( $dungeonId )
    {
        $request = $this->getStatement(
            "SELECT * FROM `$this->table_name` WHERE `Dungeon` = :dungeonId ORDER BY `Order` ASC"
        );

        $request->bindValue(":dungeonId", $dungeonId, \PDO::PARAM_INT);
        $request->execute();

        return $request->fetchAll();
    }

    public function changeSortOrder ( $encounterId, $dungeonId, $newPosition, $oldPosition )
    {
        $request = $this->getStatement(
            "UPDATE `$this->table_name` SET `Order` = :order WHERE `Id` = :id"
        );

        $position = (int) 0;
        $id = null;
        $request->bindParam(":order", $position, \PDO::PARAM_INT);
        $request->bindParam(":id", $id, \PDO::PARAM_INT);

        $position = $newPosition;
        $id = $encounterId;
        $request->execute();

        $position = (int) 0;
        $all = $this->getEncounter($dungeonId);
        foreach ($all as $step) {
            if ( $step["Id"] != $encounterId) {
                if ($position == $newPosition) {
                    $id = (int) $encounterId;
                    $request->execute();
                    $position++;
                }
                    $id = (int) $step["Id"];
                    $request->execute();
                    $position++;
            }
        }
    }

    public function changeStatus ( $id, $status )
    {
        $request = $this->getStatement("UPDATE `$this->table_name` SET `Status`=:status, `KillDate`=:KillDate WHERE `Id`=:id");
        $request->bindValue(":status", $status, \PDO::PARAM_INT);
        $request->bindValue(":id", $id, \PDO::PARAM_INT);
        if ($status == 1) {
            $time = time();
        } else {
            $time = 0;
        }
        $request->bindValue(":KillDate", $time, \PDO::PARAM_INT);
        $request->execute();
    }

    public function changeName ( $id, $name )
    {
        $request = $this->getStatement("UPDATE `$this->table_name` SET `Name`=:name WHERE `Id`=:id");
        $request->bindValue(":name", $name, \PDO::PARAM_STR);
        $request->bindValue(":id", $id, \PDO::PARAM_INT);
        $request->execute();
    }

    public function deleteUnusedEncounter ()
    {
        $this->database->query("DELETE FROM `$this->table_name` WHERE `Dungeon`='0'");
        $this->database->query("DELETE FROM `$this->table_name` WHERE `Dungeon` NOT IN (SELECT `Id` FROM `Dungeon`)");
    }

}