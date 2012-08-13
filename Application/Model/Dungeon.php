<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace Application\Model;

class Dungeon
{

    protected $db = null;
    protected $table_name = "Dungeon";

    public function __construct ()
    {
        $this->db = \Factory::getDB();
    }

    public function getActiveDungeons ()
    {
        $request = $this->db->query("SELECT * FROM `$this->table_name` WHERE `Status` = '1' ORDER BY `Order` ASC");
        return $request->fetchAll();
    }

    public function getDisabledDungeons ()
    {
        $request = $this->db->query("SELECT * FROM `$this->table_name` WHERE `Status` != '1' or `Status` IS NULL ORDER BY `Order` ASC");
        return $request->fetchAll();
    }

    public function clearAllDisabled ()
    {
        $request = $this->db->query("DELETE FROM `$this->table_name` WHERE `Status` != '1' or `Status` IS NULL;");
    }

    public function setDungeonStatus ( $id, $status )
    {
        $request = $this->db->prepare("UPDATE `$this->table_name` SET `Status` = :status WHERE `Id` = :id");
        $request->bindValue(":id", $id);
        $request->bindValue(":status", $status);
        $request->execute();
    }

    public function changeOrder ( $dungeonId, $newPosition )
    {

        $request = $this->db->prepare("SELECT `Id` FROM `$this->table_name` ORDER BY `Order` ASC");
        $request->execute();
        $result = $request->fetchAll();

        $update = $this->db->prepare("UPDATE `$this->table_name` SET `Order` = :order WHERE `Id` = :dungeonId");
        $order = 0;
        $id = null;
        $update->bindParam(":dungeonId", $id);
        $update->bindParam(":order", $order);

        foreach ( $result as $value ) {

            if ( $value["Id"] != $dungeonId ) {
                if ( $order == $newPosition ) {
                    $id = (int) $dungeonId;
                    $update->execute();
                    $order++;
                }

                $id = (int) $value["Id"];
                $update->execute();
                $order++;
            }
        }
    }

    public function editDungeonName ( $id, $name )
    {
        $request = $this->db->prepare("UPDATE `$this->table_name` SET `Name`=:name WHERE `Id`=:id");
        $request->bindValue(":id", $id);
        $request->bindValue(":name", $name);
        $request->execute();
    }

    public function createDungeon ( $name )
    {
        $request = $this->db->query("SELECT `Order` FROM `$this->table_name` ORDER BY `Order` DESC LIMIT 1");

        $result = $request->fetch();

        $last_order_number = $result["Order"] + 1;

        $request = $this->db->prepare("INSERT INTO `$this->table_name` (`Name`,`Status`, `Order`) VALUES (:name, '1', :order) ");
        $request->bindValue(":name", $name);
        $request->bindValue(":order", $last_order_number);

        $request->execute();
    }

}