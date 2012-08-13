<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace Application\Controller;

class Group extends \JackAssPHP\Core\Controller
{

    protected $rights = null;

    /**
     * Constructor
     */
    public function __construct ()
    {
        $this->loadSystem();
        $this->rights = \Factory::getRights();
    }

    public function delete ( $args )
    {
        if ( $this->rights->hasRight("admin_delete_group")
            and isset($_POST["groupid"])
        ) {

            $group_id = (int) $_POST["groupid"];
            $group = new \Application\Model\Groups();
            $group->deleteGroup($group_id);
        }
    }

    public function edit ( $args )
    {
        if ( $this->rights->hasRight("admin_edit_group")
            and isset($_POST["groupid"])
            and isset($_POST["name"])
            and strlen($_POST["name"] > 0)
        ) {

            $group_id = (int) $_POST["groupid"];
            $name = $_POST["name"];

            $group = new \Application\Model\Groups();

            $group->editGroup($group_id, $name);
        }
    }

    public function create ( $args )
    {
        if ( $this->rights->hasRight("admin_create_group")
            and isset($_POST["name"])
            and strlen($_POST["name"]) > 0
        ) {
            $name = $_POST["name"];
            $group = new \Application\Model\Groups();
            $group->createGroup($name);
        }
    }

}