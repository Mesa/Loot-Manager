<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace Application\Model;

class GuildRanks
{

    protected $db = null;
    protected $table_name = "guild_ranks";
    protected $ranks = array( );
    protected $updateRank = null;
    protected $insertRank = null;

    public function __construct ()
    {
        $this->db = \Factory::getDB();
    }

    public function getDisplayRanks ()
    {
        $request = $this->db->prepare("SELECT `Id` FROM `$this->table_name` WHERE `display` = 1");
        $request->execute();

        while ( $rank = $request->fetch() ) {
            $display[] = $rank["Id"];
        }
        return $display;
    }

    public function getRankById ( $id )
    {
        if ( $this->ranks == null ) {
            $this->getRanksfromDb();
        }

        if ( isset($this->ranks[$id]) ) {
            return $this->ranks[$id];
        }
    }

    public function getRankId ( $name )
    {
        if( empty($this->getRankIdRequest)) {
            $this->getRankIdRequest = $this->db->prepare("SELECT * FROM `$this->table_name` WHERE `Name` = :name LIMIT 1");
        }

        $this->getRankIdRequest->bindParam(":name", $name);
        $this->getRankIdRequest->execute();

        return $this->getRankIdRequest->fetch();
    }

    protected function getRanksfromDb ()
    {
        $request = $this->db->query("SELECT * FROM `$this->table_name` ORDER BY `Id` ASC");
        $request->execute();

        while ( $rank = $request->fetch() ) {
            $this->ranks[$rank["Id"]] = $rank;
        }
    }

    public function getAllRanks ()
    {
        if ( $this->ranks == null ) {
            $this->getRanksfromDb();
        }
        return $this->ranks;
    }

    public function insertRank ( $name )
    {
        $check = $this->getRankId($name);
        if( $check === false) {
            if ( empty($this->insertRank) ) {
                $this->insertRank = $this->db->prepare("INSERT INTO `$this->table_name` (`Id`, `Name`) VALUES (NULL, :name)");
            }
            $this->insertRank->bindValue(":name", $name);
            $this->insertRank->execute();
        } else {
            return false;
        }
    }

    public function updateRank ( $id, $name )
    {
        if ( $this->updateRank == null ) {
            $this->updateRank = $this->db->prepare("UPDATE `$this->table_name` SET `Name` = :name WHERE `Id`= :id LIMIT 1;");
        }
        $this->updateRank->bindValue(":name", $name, \PDO::PARAM_STR);
        $this->updateRank->bindValue(":id", $id, \PDO::PARAM_INT);

        $this->updateRank->execute();
    }

    public function showRank ( $id )
    {
        if ( empty($this->showRequest) ) {
            $this->showRequest = $this->db->prepare("UPDATE `$this->table_name` SET `display` = 1 WHERE `Id` = :Id LIMIT 1;");
        }
        $this->showRequest->bindValue(":Id", $id);
        $this->showRequest->execute();
    }

    public function getRankName ( $id )
    {
        /**
         * @todo save RankName to array, to avoid the request run twice for the same ID
         */
        if ( empty($this->RankNameRequest) ) {
            $this->RankNameRequest = $this->db->prepare("SELECT `Name` FROM `$this->table_name` WHERE `Id` = :rankId LIMIT 1");
        }
        $this->RankNameRequest->bindParam(":rankId", $id, \PDO::PARAM_INT);

        return $this->RankNameRequest->fetch();
    }

    public function hideRank ( $id )
    {
        if ( empty($this->hideRequest) ) {
            $this->hideRequest = $this->db->prepare("UPDATE `$this->table_name` SET `display` = NULL WHERE `Id` = :Id LIMIT 1;");
        }
        $this->hideRequest->bindValue(":Id", $id, \PDO::PARAM_INT);
        $this->hideRequest->execute();
    }

}