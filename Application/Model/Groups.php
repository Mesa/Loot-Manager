<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace Application\Model;

class Groups
{

    /**
     * Contains the MySQL connection Object
     *
     * @var [Object] MySQL connection
     */
    protected $db;
    protected $table_name = "groups";

    /**
     * Constructor
     */
    public function __construct ()
    {
        $this->db = \Factory::getDB();
    }

    /**
     * Returns all Groups in an Array
     *
     * @return [Array] All Groups in DB
     */
    public function getGroupList ()
    {
        $request = $this->db->prepare("SELECT * FROM `$this->table_name`;");
        $request->execute();
        return $request->fetchAll();
    }

    public function getGroupsByUserId ( $user_id )
    {
        $request = $this->db->prepare("SELECT `GroupId` Id, `Name` FROM `v_user_groups`, `$this->table_name` WHERE `UserId` = :user_id and `GroupId` = `$this->table_name`.`Id`;");
        $request->bindParam(":user_id", $user_id, \PDO::PARAM_INT);
        $request->execute();

        return $request->fetchAll();
    }

    public function getUserByGroupId ( $group_id )
    {
        $request = $this->db->prepare("SELECT `Name`, `UserId` id FROM `user`, `v_user_groups` WHERE `GroupId` = :group_id and `user`.`id` = `UserId`");
        $request->bindParam(":group_id", $group_id, \PDO::PARAM_INT);

        try {
            $request->execute();
        } catch ( \PDOException $exc ) {
            return false;
        }
        return $request->fetchAll();
    }

    public function getUserNotInGroup ( $group_id )
    {
        $request = $this->db->prepare("SELECT `Name`, `Id` id FROM `user` WHERE `UserName` != 'Guest' AND `Id` not in (SELECT `UserId` Id From `v_user_groups` WHERE `GroupId` = :group_id)");
        $request->bindParam(":group_id", $group_id, \PDO::PARAM_INT);

        try {
            $request->execute();
        } catch ( \PDOException $exc ) {
            return false;
        }
        return $request->fetchAll();
    }

    public function getMissingGroupsByIserId ( $user_id )
    {
        $request = $this->db->prepare("SELECT `Id`, `Name` FROM `$this->table_name` WHERE `Id` not in (SELECT `GroupId` FROM `v_user_groups` WHERE `UserId` = :user_id) ");
        $request->bindParam(":user_id", $user_id, \PDO::PARAM_INT);

        $request->execute();
        return $request->fetchAll();
    }

    public function countMember ( $groupId )
    {
        $request = $this->db->prepare("SELECT `GroupId`, `Name` FROM `v_user_groups`, `$this->table_name` WHERE `UserId` = :user_id and `GroupId` = `$this->table_name`.`Id`;");
        $request->bindParam(":user_id", $user_id, \PDO::PARAM_INT);
        $request->execute();

        return $request->fetchAll();
    }

    public function getGroupNameById ( $group_id )
    {
        if ( empty($this->groupNameStatement)) {
            $this->groupNameStatement = $this->db->prepare("SELECT `Name` FROM `groups` WHERE `Id` = :group_id;");
        }

        $this->groupNameStatement->bindValue(":group_id", $group_id);
        $this->groupNameStatement->execute();
        $data = $this->groupNameStatement->fetch();

        return $data["Name"];
    }

    public function addUserToGroup ( $group_id, $user_id )
    {
        $request = $this->db->prepare("INSERT INTO `v_user_groups` (`UserId`, `GroupId`) VALUES (:user_id, :group_id);");
        $request->bindParam(":user_id", $user_id, \PDO::PARAM_INT);
        $request->bindParam(":group_id", $group_id, \PDO::PARAM_INT);

        try {
            $request->execute();
        } catch ( \PDOException $exc ) {
            return false;
        }

        return true;
    }

    public function removeUserFromGroup ( $group_id, $user_id )
    {
        $request = $this->db->prepare("DELETE FROM `v_user_groups` WHERE `UserId` = :user_id and `GroupId` = :group_id LIMIT 1;");
        $request->bindParam(":user_id", $user_id, \PDO::PARAM_INT);
        $request->bindParam(":group_id", $group_id, \PDO::PARAM_INT);

        try {
            $request->execute();
        } catch ( \PDOException $exc ) {
            return false;
        }

        return true;
    }

    public function deleteGroup ( $group_id )
    {
        $request = $this->db->prepare("DELETE FROM `$this->table_name` WHERE `Id` = :group_id LIMIT 1;");
        $request->bindParam(":group_id", $group_id, \PDO::PARAM_INT);

        try {
            $request->execute();
        } catch ( \PDOException $exc ) {
            return false;
        }

        $request = $this->db->prepare("DELETE FROM `v_user_groups` WHERE `GroupId` = :group_id");
        $request->bindParam(":group_id", $group_id, \PDO::PARAM_INT);

        try {
            $request->execute();
        } catch ( \PDOException $exc ) {
            return false;
        }

        $request = $this->db->prepare("DELETE FROM `v_group_rights` WHERE `GroupId` = :group_id");
        $request->bindParam(":group_id", $group_id, \PDO::PARAM_INT);

        try {
            $request->execute();
        } catch ( \PDOException $exc ) {
            return false;
        }

        return true;
    }

    public function editGroup ( $group_id, $name )
    {
        $request = $this->db->prepare(
                "UPDATE `$this->table_name`
                SET
                `Name` = :name,
                WHERE `Id`= :group_id;"
        );
        $request->bindParam(":group_id", $group_id, \PDO::PARAM_INT);
        $request->bindParam(":name", $name, \PDO::PARAM_STR);

        try {
            $request->execute();
        } catch ( \PDOException $exc ) {
            return false;
        }

        return true;
    }

    public function createGroup ( $name )
    {
        $request = $this->db->prepare(
                "INSERT INTO
                `$this->table_name`
                (`Name`)
                VALUES
                (:name);"
        );
        $request->bindParam(":name", $name, \PDO::PARAM_STR);
        $request->execute();
    }

}