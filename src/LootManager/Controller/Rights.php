<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace Application\Controller;

use Application\Model\User as UserModel;
use Application\Model\Rights as RightsModel;
use Application\Model\Groups as GroupModel;

/**
 * Loot-Manager
 *
 * @author   Mesa <daniel.langemann@gmx.de>
 */
class Rights extends \JackAssPHP\Core\Controller
{

    protected $rigths = null;

    /**
     * Constructor
     */
    public function __construct ()
    {
        $this->loadSystem();
        $this->rights = \Factory::getRights();
        $this->user = \Factory::getUser();
    }

    /**
     * Show the Rights Dashboard
     *
     * @return void
     */
    public function index ( $args = null )
    {
        if (
                $this->rights->hasRight("rights_access_dashboard") ||
                $this->rights->hasRight("admin_delete_user") ||
                $this->rights->hasRight("admin_edit_user") ||
                $this->rights->hasRight("admin_create_user") ||
                $this->rights->hasRight("admin_add_remove_user_to_group")
        ) {

            $template = \Factory::getView();
            $page     = \Factory::getHtmlResponse();
            $page->setTemplate("AdminLayout.html");
            $page->setTitle($this->registry->get("SYSTEM_NAME"));
            $page->addJavascript($template->load("Rights/rights.js"));
            $page->addStyleSheet($template->load("Rights/css"));

            $content = \Factory::getView();

            $template->rights = $this->rights;

            $userDao = new UserModel();
            $user_list = $userDao->getUserList();

            $groupDoa = new GroupModel();
            $group_list = $groupDoa->getGroupList();

            $content->user_block = "";

            foreach ( $user_list as $user ) {
                $template->user_data = $user;
                $template->is_guest = $userDao->isGuestAccount($user["Id"]);
                $content->user_block .= $template->load("Rights/user_block");
            }

            $content->group_block = "";

            foreach ( $group_list as $group ) {
                $template->group_id = $group["Id"];
                $template->group_name = $group["Name"];
                $content->group_block .= $template->load("Rights/group_block");
            }

            $page->addToContent($template->load("Rights/sub_menu"));
            $page->addToContent($content->load("Rights/content"));

        } else {
            throw new \RightException("rights_access_dashboard");
        }
    }

    public function showRightsMenu ( $args )
    {
        if ( $this->rights->hasRight("rights_edit_user_rights") ||
                $this->rights->hasRight("rights_edit_group_rights") ) {

            $template = \Factory::getView();
            /**
             * We expect an u or g
             * u = user
             * g = group
             */
            $type = $args["type"];
            $id = $args["id"];
            $error = false;

            $rightsDao = new RightsModel();
            switch ( $type ) {
                case "u":
                    $right_list = $rightsDao->getUserRights($id);
                    $template->type = "user";
                    $userDoa = new UserModel();
                    $template->name = $userDoa->getUserNameById($id);
                    break;
                case "g":
                    $right_list = $rightsDao->getGroupRights($id);
                    $template->type = "group";
                    $groupDao = new GroupModel();
                    $template->name = $groupDao->getGroupNameById($id);
                    break;
                default:
                    $error = true;
                    break;
            }

            if ( $error === false ) {

                $all_rights = $rightsDao->getAllRights();

                $template->all_rights = $all_rights;
                $template->right_list = $right_list;
                $template->id = $id;
                echo $template->load("Rights/rights_menu");
            } else {
                echo "Wrong Parameter";
            }
        } else {
             throw new \RightException("rights_edit_user_rights");
        }
    }

    /**
     * Ajax request to add Right to a User
     *
     * @param type $args Url arguments ( User ID and Right ID )
     *
     * @return void
     */
    public function addUserRight ( $args )
    {
        if ( $this->rights->hasRight("rights_edit_user_rights") ) {

            $user_id = (int) $args["user_id"];
            $right_name = $args["right_name"];

            $rights = new RightsModel();
            $rights->setUserRight($user_id, $right_name);
        } else {
            throw new \RightException("rights_edit_user_rights");
        }
    }

    /**
     * Ajax request to add Right to a Group
     *
     * @param type $args Url arguments ( Group ID and Right ID )
     *
     * @return void
     */
    public function addGroupRight ( $args )
    {
        if ( $this->rights->hasRight("rights_edit_group_rights") ) {
            $group_id = (int) $args["group_id"];
            $right_name = $args["right_name"];

            $rights = new RightsModel();
            $rights->setGroupRight($group_id, $right_name);
        } else {
            throw new \RightException("rights_edit_group_rights");
        }
    }

    /**
     *Ajax request to remove one group right.
     *
     * @param [Array] $args (Group ID, right name)
     *
     * @return void
     */
    public function deleteGroupRight ( $args )
    {
        if ( $this->rights->hasRight("rights_edit_group_rights") ) {
            $group_id = (int) $args["group_id"];
            $right_name = $args["right_name"];

            $rights = new RightsModel();
            $rights->deleteGroupRight($group_id, $right_name);
        } else {
            throw new \RightException("rights_edit_group_rights");
        }
    }

    /**
     *
     * @param type $args
     */
    public function deleteUserRight ( $args )
    {
        if ( $this->rights->hasRight("rights_edit_user_rights") ) {
            $user_id = (int) $args["user_id"];
            $right_name = $args["right_name"];

            $rights = new RightsModel();
            $rights->deleteUserRight($user_id, $right_name);
        } else {
            throw new \RightException("rights_edit_user_rights");
        }
    }

}