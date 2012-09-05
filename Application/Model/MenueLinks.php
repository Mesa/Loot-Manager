<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace Application\Model;

class MenueLinks extends \JackAssPHP\Core\DataModel
{

    protected $table_name = "menue_links";

    /**
     * Get all Links stored in db
     *
     * @return [Array]
     */
    public function getAllLinks ()
    {
        $request = $this->getStatement(
            "SELECT * FROM `$this->table_name` ORDER BY `Order` ASC"
        );

        $request->execute();
        return $request->fetchAll();
    }

    public function loadDropDownMenu ( )
    {
        $request = $this->getStatement(
            "SELECT `Name` name, `Path` link FROM `$this->table_name` ORDER BY `Order` ASC"
        );

        $request->execute();
        return $request->fetchAll();
    }
    /**
     * Get Item by name
     *
     * @param [String] $name Item name
     *
     * @return [Array]
     */
    public function byName ( $name )
    {
        $request = $this->getStatement(
            "SELECT * FROM `$this->table_name` WHERE `Name` = :name;"
        );
        $request->bindValue(":name", $name);
        $request->execute();
        return $request->fetchAll();
    }

    /**
     * Get item identified by id
     *
     * @param [Int] $id Item id
     *
     * @return [Array] Data Array
     */
    public function byId ( $id )
    {
        $request = $this->getStatement(
            "SELECT * FROM `$this->table_name` WHERE `Id` = :id;"
        );

        $request->bindValue(":id", $id, \PDO::PARAM_INT);
        $request->execute();
        return $request->fetch();
    }

    /**
     * Insert new item into DB
     *
     * @param [String] $name Item Name
     * @param [String] $path URL
     *
     * @return void
     */
    public function add ( $name, $path )
    {
        $request = $this->getStatement(
            "INSERT INTO `$this->table_name` (`Name`, `Path`, `Order`) VALUES (:name, :path, :order);"
        );
        $last_order = $this->getLastOrder();
        $request->bindValue(":name", $name, \PDO::PARAM_STR);
        $request->bindValue(":path", $path, \PDO::PARAM_STR);
        $request->bindValue(":order", $last_order, \PDO::PARAM_INT);
        return $request->execute();
    }

    public function getLastOrder () 
    {
        $request = $this->getStatement("SELECT `Order` FROM `$this->table_name` ORDER BY `Order` DESC LIMIT 1");
        $request->execute();

        $order = $request->fetch();
        return (int) $order["Order"] + 1;
    }

    /**
     * Update item name identified by Id
     *
     * @param [Int]    $id   Item Id
     * @param [String] $name Item Name
     *
     * @return void
     */
    public function updateName ( $id, $name )
    {
        $request = $this->getStatement(
            "UPDATE `$this->table_name` SET `Name` = :name WHERE `Id` = :id"
        );

        $request->bindValue(":name", $name, \PDO::PARAM_STR);
        $request->bindValue(":id", $id, \PDO::PARAM_INT);
        return $request->execute();
    }

    public function updateRight ( $id, $right ) {
        $request = $this->getStatement(
            "UPDATE `$this->table_name` SET `Right` = :right WHERE `Id` = :id"
        );
        $request->bindValue(":id", $id);
        $request->bindValue(":right", $right);
        return $request->execute();
    }
    /**
     * Update Path Info of Item identified by Id
     *
     * @param [Int]    $id   Item id
     * @param [String] $path URL
     *
     * @return void
     */
    public function updatePath ( $id, $path )
    {
        $request = $this->getStatement(
            "UPDATE `$this->table_name` SET `Path` = :path WHERE `Id` = :id"
        );

        $request->bindValue(":id", $id, \PDO::PARAM_INT);
        $request->bindValue(":path", $path, \PDO::PARAM_STR);
        return $request->execute();
    }

    /**
     * Item anhand der Id löschen.
     *
     * @param [Int] $id Item Id
     *
     * @return void
     */
    public function remove ( $id )
    {
        $request = $this->getStatement("DELETE FROM `$this->table_name` WHERE `Id` = :id");
        $request->bindValue(":id", $id, \PDO::PARAM_INT);
        return $request->execute();
    }

    /**
     * Rebiuld the Menu Link order in the Table an move the selected Item
     * to a new Position.
     *
     * @param [Int] $itemId      Item Id in DB
     * @param [Int] $newPosition Number of the new Position.
     *
     * @return void
     */
    public function updateOrder ( $itemId, $newPosition )
    {
        $request = $this->getStatement(
            "UPDATE `$this->table_name` SET `Order` = :order WHERE `Id` = :id"
        );

        $request->bindParam(":order", $position, \PDO::PARAM_INT);
        $request->bindParam(":id", $id, \PDO::PARAM_INT);

        /**
         * Sollte das Element auf die letzte Position verschoben werden,
         * dann wird die Schleife einmal zu wenig durchlaufen, da das Element
         * in dem Array Ignoriert wird.
         */
        $position = $newPosition;
        $id = $itemId;
        $request->execute();

        $position = (int) 0;
        $all = $this->getAllLinks();

        foreach ($all as $step) {
            if ( $step["Id"] != $itemId) {
                if ($position == $newPosition) {
                    $id = (int) $itemId;
                    $request->execute();
                    $position++;
                }
                    $id = (int) $step["Id"];
                    $request->execute();
                    $position++;
            }
        }
    }

}
