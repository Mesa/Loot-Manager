<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace Application\Model;

use Application\Model\GuildRanks as Ranks;

class RiftXml extends Chars
{

    protected $filename = null;
    protected $guild_name = null;
    protected $db = null;
    protected $char_table = "chars";
    protected $auto_insert_by_rank = array(0, 1, 2, 3, 4, 5, 6, 7, 8);

    public function __construct ( $filename )
    {
        parent::__construct();
        $this->db = \Factory::getDB();

        $this->filename = $filename;
        $this->getCharsFromDB();
        $this->walkRankArray();
        $this->walkMemberArray();
    }

    protected function walkRankArray ()
    {
        $rankModel = new Ranks();
        $this->auto_insert_by_rank = $rankModel->getAutoInsertranks();
        $this->ranks = $rankModel->getAllRanks();
        $xml = simplexml_load_file($this->filename);

        foreach ( $xml->Ranks->Rank as $rank ) {

            if ( isset($this->ranks[(int) $rank->Id]) ) {
                $rankModel->updateRank((int) $rank->Id, (string) $rank->Name);
            } else {
                $rankModel->insertRank((int) $rank->Id, (string) $rank->Name);
            }
        }
    }

    protected function getCharsFromDB ()
    {
        $request = $this->db->query("SELECT * FROM `$this->char_table`");
        $request->execute();

        $result = $request->fetchAll();
        foreach ( $result as $member ) {
            $this->members[$member["Name"]] = $member;
        }
    }

    protected function walkMemberArray ()
    {
        $xml = simplexml_load_file($this->filename);
        $this->guild_name = (string) $xml->Name;
        $update_user = $this->db->prepare(
                "Update `$this->char_table` SET
                `Class` = :class,
                `guild_rank` = :guild_rank,
                `joined` = :joined,
                `lastLogOutTime` = :lastLogout,
                `personalNotes` = :personal_notes,
                `officerNotes` = :officer_notes,
                `guild_name` = :guild_name,
                `level` = :level
                WHERE `Name` = :char_name LIMIT 1"
        );

        $drop_from_guild = $this->db->prepare(
                "UPDATE `$this->char_table` SET
                `guild_rank` = NULL,
                `joined` = NULL,
                `personalNotes` = NULL,
                `officerNotes` = NULL,
                `guild_name` = NULL
                WHERE `Id` = :id LIMIT 1"
                );

        $insert_user = $this->db->prepare(
                "INSERT INTO `$this->char_table`
                (
                `Name`,
                `Class`,
                `guild_rank`,
                `joined`,
                `lastLogOutTime`,
                `personalNotes`,
                `officerNotes`,
                `level`,
                `guild_name`
                ) VALUES (
                :char_name,
                :class,
                :guild_rank,
                :joined,
                :lastLogout,
                :personal_notes,
                :officer_notes,
                :level,
                :guild_name
                );"
        );

        foreach ( $xml->Members->Member as $member ) {
            if ( $this->isInDB((string) $member->Name) ) {
                /**
                 * Char ist in der DB, aktuallisieren
                 */
                $update_user->bindValue(":class", $member->Calling);
                $update_user->bindValue(":guild_rank", (int) $member->Rank);
                $update_user->bindValue(":level", (int) $member->Level);
                $update_user->bindValue(":joined", $this->parseTimestamp($member->Joined));
                $update_user->bindValue(":lastLogout", $this->parseTimestamp($member->LastLogOutTime));
                $update_user->bindValue(":guild_name", $this->guild_name);
                $update_user->bindValue(":personal_notes", $member->PersonalNotes);
                $update_user->bindValue(":officer_notes", $member->OfficerNotes);
                $update_user->bindValue(":char_name", $member->Name);
                $update_user->execute();
            } else {
                /**
                 * Char existiert noch nicht in der DB und Gildenrang ist in
                 * auto_insert_by_rank
                 */
                if ( in_array((int) $member->Rank, $this->auto_insert_by_rank) ) {
                        $insert_user->bindValue(":class", $member->Calling);
                        $insert_user->bindValue(":guild_rank", (int) $member->Rank);
                        $insert_user->bindValue(":level", (int) $member->Level);
                        $insert_user->bindValue(":joined", $this->parseTimestamp($member->Joined));
                        $insert_user->bindValue(":lastLogout", $this->parseTimestamp($member->LastLogOutTime));
                        $insert_user->bindValue(":guild_name", $this->guild_name);
                        $insert_user->bindValue(":personal_notes", $member->PersonalNotes);
                        $insert_user->bindValue(":officer_notes", $member->OfficerNotes);
                        $insert_user->bindValue(":char_name", $member->Name);
                        $insert_user->execute();
                    }
            }
        }

        /**
         * Alle chars die noch übrig sind, sind nicht, bzw nicht mehr in der Gilde
         */
        foreach ( $this->members as $member ) {
            $drop_from_guild->bindValue(":id", $member["Id"]);
            $drop_from_guild->execute();
        }
    }

    protected function parseTimestamp ( $string )
    {
        $time = preg_match('/(?<year>\d{4})-(?<month>\d{2})-(?<day>\d{2})T(?<hours>\d{2}):(?<minutes>\d{2}):(?<seconds>\d{2}).+/', (string) $string, $matches);
        return mktime($matches["hours"], $matches["minutes"], $matches["seconds"], $matches["month"], $matches["day"], $matches["year"]);
    }

    protected function isInDB ( $member_name )
    {
        if ( isset($this->members[$member_name]) ) {
            unset($this->members[$member_name]);
            return true;
        } else {
            return false;
        }
    }
}