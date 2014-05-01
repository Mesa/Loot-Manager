<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace LootManager\Model;

use JackAssPHP\Core\DataModel;

class Rights extends DataModel
{

    /**
     * @Inject("EntityManager")
     */
    public $database;
    protected $rights = null;
    protected $DS_rights = "rights";

    public function getAllRights()
    {
        if (count($this->rights) == 0) {

            $request = $this->database->prepare("SELECT * FROM `$this->DS_rights` ORDER BY `name` ASC");
            $request->execute();

            $data = $request->fetchAll();

            foreach ($data as $right) {
                $this->rights[$right["Group"]][] = $right;
            }
        }

        return $this->rights;
    }

    /**
     * Lädt alle Rechte die einem einzelnen Benutzer zugeordnet sind
     *
     * @param $user_id
     *
     * @return mixed [Array]
     */
    public function getUserRights($user_id)
    {
        if (empty($this->userRightsStatement)) {
            $this->userRightsStatement = $this->database->prepare(
                                                        "SELECT `RightId` FROM v_user_rights WHERE `v_user_rights`.`UserId` = :user_id;"
            );
        }
        $this->userRightsStatement->bindValue(":user_id", $user_id);
        $this->userRightsStatement->execute();

        $data = $this->userRightsStatement->fetchAll();

        foreach ($data as $right) {
            $rights[$right["RightId"]] = true;
        }

        return $rights;
    }

    /**
     * Lädt alle Rechte die einer einzelnen Gruppe zugeordnet sind.
     *
     * @param $group_id
     *
     * @return mixed [Array]
     */
    public function getGroupRights($group_id)
    {
        if (empty($this->groupRightsStatement)) {
            $this->groupRightsStatement = $this->database->prepare(
                                                         "SELECT `RightId` FROM v_group_rights WHERE `v_group_rights`.`GroupId` = :group_id;"
            );
        }
        $this->groupRightsStatement->bindValue(":group_id", $group_id);
        $this->groupRightsStatement->execute();

        $data = $this->groupRightsStatement->fetchAll();

        foreach ($data as $right) {
            $rights[$right["RightId"]] = true;
        }

        return $rights;
    }

    public function setGroupRight($group_id, $right_name)
    {
        $request = $this->getStatement(
                        "INSERT INTO `v_group_rights` (`GroupId`, `RightId`) VALUES (:group_id, (SELECT `Id` FROM `$this->DS_rights` WHERE `name` = :right_name));"
        );
        $request->bindParam(":group_id", $group_id);
        $request->bindParam(":right_name", $right_name);
        $request->execute();
    }

    public function deleteGroupRight($group_id, $right_name)
    {
        $request = $this->getStatement(
                        "DELETE FROM `v_group_rights` WHERE `GroupId` = :group_id and `RightId` = (SELECT `Id` FROM `$this->DS_rights` WHERE `name` = :right_name);"
        );
        $request->bindParam(":group_id", $group_id);
        $request->bindParam(":right_name", $right_name);
        $request->execute();
    }

    public function setUserRight($user_id, $right_name)
    {
        $request = $this->getStatement(
                        "INSERT INTO `v_user_rights` (`UserId`, `RightId`) VALUES (:user_id, (SELECT `Id` FROM `$this->DS_rights` WHERE `name` = :right_name));"
        );
        $request->bindParam(":user_id", $user_id);
        $request->bindParam(":right_name", $right_name);
        $request->execute();
    }

    public function deleteUserRight($user_id, $right_name)
    {
        $request = $this->getStatement(
                        "DELETE FROM `v_user_rights` WHERE `UserId` = :user_id and `RightId` = (SELECT `Id` FROM `$this->DS_rights` WHERE `name` = :right_name);"
        );
        $request->bindParam(":user_id", $user_id);
        $request->bindParam(":right_name", $right_name);
        $request->execute();
    }

    public function byId($id)
    {
        $request = $this->getStatement("SELECT * FROM `$this->DS_rights` WHERE `Id` = :id");
        $request->bindValue(":id", $id);
        $request->execute();

        return $request->fetch();
    }
}