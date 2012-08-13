<?php

/**
 * Loot Manager
 * Copyright (C) 2011  Mesa <Daniel Langemann>
 *
 * Php version 5.3
 *
 * @category Helper
 * @package  Loot_Manager
 * @author   Mesa <daniel.langemann@gmx.de>
 * @link     http://www.loot-manager.com
 */

namespace JackAssPHP\Helper;

/**
 * Verwaltet die Rechte des Benutzers und bietet eine einfach Schnittstelle
 * um vorhandene Rechte abzufragen
 */
class Rights
{

    protected $user_id = null;
    protected $database = null;
    protected $group_id = null;
    protected $rights = array();
    protected $table_name = "rights";

    public function __construct ($db_connection)
    {
        $this->database = $db_connection;
        $this->user = \Factory::getUser();
        $this->user_id = $this->user->getUserId();
        $this->getGroupRights();
        $this->getUserRights();
    }


    /**
     * Lädt alle Gruppenrechte aus der Datenbank.
     *
     * @return void
     */
    protected function getGroupRights ()
    {
        $request = $this->database->prepare("SELECT `Name` FROM `$this->table_name` WHERE `Id` IN (SELECT `RightId` FROM `v_group_rights` WHERE `GroupId` IN (SELECT `GroupId` FROM `v_user_groups` WHERE `UserId`= :user_id));");
        $request->bindParam(":user_id", $this->user_id);
        $result = $request->execute();

        $result = $request->fetchAll();

        foreach ($result as $key => $value) {
            $this->setRight($value["Name"]);
        }
    }

    /**
     * Lädt alle Benutzerrechte aus der Datenbank. Überschreibt ggf die Gruppenrechte
     *
     * @return void
     */
    protected function getUserRights ()
    {
        $request = $this->database->prepare("SELECT `Name` FROM `$this->table_name` WHERE `Id` in (SELECT `RightId` FROM `v_user_rights` WHERE `UserId`= :user_id)");
        $request->bindParam(":user_id", $this->user_id);
        $request->execute();
        $result = $request->fetchAll();

        foreach ($result as $key => $value) {
            $this->setRight($value["Name"]);
        }
    }

    /**
     * Recht setzen.
     *
     * @param [String] $right Right name
     *
     * @return [Object] $this
     */
    public function setRight ( $right )
    {
        $name = strtolower($right);
        $this->rights[$name] = true;
        return $this;
    }

    /**
     * Remove Right from user
     *
     * @param type $right name
     *
     * @return [Object] $this
     */
    public function removeRight ( $right )
    {
        $name = strtolower($right);
        $this->rights[$name] = false;
        return $this;
    }

    /**
     * Prüfen ob der Benutzer ein bestimmtes Recht hat.
     *
     * @param [String] $right    Name des Recht
     * @param [Bool]   $redirect send user to login page.
     *
     * @return [Bool] true wenn der Benutzer das Recht hat.
     */
    public function hasRight ( $right )
    {
        $name = strtolower($right);

        if (isset($this->rights[$name]) and $this->rights[$name]) {
            return true;
        } else {
            return false;
        }
    }
}