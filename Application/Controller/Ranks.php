<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace Application\Controller;

use Application\Model\GuildRanks as GuildRanks;

class Ranks extends \JackAssPHP\Core\Controller
{
    protected $rights = null;

    public function __construct ()
    {
        $this->loadSystem();
        $this->rights = \Factory::getRights();
    }

    public function index ( $args = null )
    {

    }

    public function hideRank ()
    {
        if ( $this->rights->hasRight("edit_rooster_config")) {
            $rank_name = $_POST["name"];
            $ranks = new GuildRanks();
            $ranks->hideRank($rank_name);
        }
    }

    public function showRank ()
    {
        if ( $this->rights->hasRight("edit_rooster_config")) {
            $rank_name = $_POST["name"];
            $ranks = new GuildRanks();
            $ranks->showRank($rank_name);
        } else {
            echo "false";
        }
    }

    public function addAutoInsert ( $args = null )
    {
        if ( $this->rights->hasRight("edit_rooster_config")) {
            $rank_name = $_POST["name"];
            $ranks = new GuildRanks();
            $ranks->addAutoInsert($rank_name);
        }
    }

    public function removeAutoInsert ( $args = null )
    {
        if ( $this->rights->hasRight("edit_rooster_config")) {
            $rank_name = $_POST["name"];
            $ranks = new GuildRanks();
            $ranks->removeAutoInsert($rank_name);
        }
    }

}