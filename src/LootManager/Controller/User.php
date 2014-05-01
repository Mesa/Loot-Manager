<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace Application\Controller;

use Application\Model\User as UserModel;

class User extends \JackAssPHP\Core\Controller
{
    protected $lang;

    /**
     * Constructor
     */
    public function __construct ()
    {
        $this->loadSystem();
        $this->lang = \Factory::getTranslate();
    }

    protected function createUsername ( $string )
    {
        $hashed_username = base64_encode($string);
        return $hashed_username;
    }

    protected function createPassword ( $string )
    {
        $hashed_string = hash('sha256', $string);
        return $hashed_string;
    }

    public function createUser ( $args )
    {
        $rights = \Factory::getRights();

        if ($rights->hasRight("admin_create_user")) {

            if (strlen($_POST["password"]) >= $this->registry->get("MIN_PASSWORD_LENGTH")) {
                $username = $this->createUsername($_POST["user_name"]);
                $password = $this->createPassword($_POST["password"]);
                $name = $_POST["displayname"];
                $email = $_POST["e_mail"];
                /**
                 * Unlock the the user
                 */
                $status = 1;

                $user = new UserModel();
                $user->createUser(
                        $username, $password, $email, $name, $status
                );
            }
        } else {
            $lang = \Factory::getTranslate();
            echo $lang->translate("insufficient_rights");
        }
    }

    /**
     * Delete user from DB
     *
     * @return void
     */
    public function deleteUser ()
    {
        $rights = \Factory::getRights();

        if ($rights->hasRight("admin_delete_user")) {
            /**
             * Test damit nicht der GastAccount bearbeitet werden kann
             */
            $user_dao = new UserModel();

            if (isset($_POST["userid"]) and $user_dao->isGuestAccount($_POST["userid"]) === false ) {
                $user_id = (int) $_POST["userid"];

                $user = new UserModel();
                $user->deleteUser($user_id);
            }
        } else {
            echo $this->lang->translate("INSUFFICIENT_RIGHTS");
        }
    }

    /**
     * Returns a JSON formated Array for user Groups
     *
     * @param type $args User ID
     *
     * @return [String/JSON] User groups
     */
    public function getUserGroups ( $args )
    {
        $rights = \Factory::getRights();

        if (
                $rights->hasRight("admin_add_remove_user_to_group")
                and isset($_POST["userid"])
        ) {

            $user_id = (int) $_POST["userid"];
            $groups = new \Application\Model\Groups();

            $data["active_groups"] = $groups->getGroupsByUserId($user_id);
            $data["passive_groups"] = $groups->getMissingGroupsByIserId($user_id);

            echo json_encode($data);
        }
    }

    public function removeFromGroup ( $args )
    {
        $rights = \Factory::getRights();

        if ($rights->hasRight("admin_add_remove_user_to_group")
                and isset($_POST["userid"])
                and isset($_POST["groupid"])
        ) {

            $user_id = (int) $_POST["userid"];
            $group_id = (int) $_POST["groupid"];
            echo $user_id . " - " . $group_id;
            $groups = new \Application\Model\Groups();
            $response = $groups->removeUserFromGroup($group_id, $user_id);
        }
    }

    public function addToGroup ( $args )
    {
        $rights = \Factory::getRights();

        if ($rights->hasRight("admin_add_remove_user_to_group")
                and isset($_POST["userid"])
                and isset($_POST["groupid"])
        ) {
            /**
             * Test damit nicht der GastAccount bearbeitet werden kann
             */
            $user_dao = new UserModel();

            if (!$user_dao->isGuestAccount($_POST["userid"])) {
                $user_id = (int) $_POST["userid"];
                $group_id = (int) $_POST["groupid"];

                $groups = new \Application\Model\Groups();
                $response = $groups->addUserToGroup($group_id, $user_id);
            }
        }
    }

    public function getGroupUser ( $args )
    {
        $rights = \Factory::getRights();

        if ($rights->hasRight("admin_add_remove_user_to_group")
                and isset($_POST["groupid"])
        ) {
            $group_id = (int) $_POST["groupid"];

            $groups = new \Application\Model\Groups();
            $data["active_member"] = $groups->getUserByGroupId($group_id);
            $data["all_member"] = $groups->getUserNotInGroup($group_id);

            echo json_encode($data);
        }
    }

    public function editUser ()
    {
        $rights = \Factory::getRights();
        if ($rights->hasRight("admin_edit_user")
                and isset($_POST["username"])
                and isset($_POST["new_password"])
                and isset($_POST["shown_name"])
                and isset($_POST["email"])
                and isset($_POST["user_id"])
        ) {
            /**
             * Test damit nicht der GastAccount bearbeitet werden kann
             */
            $user_dao = new UserModel();

            if (!$user_dao->isGuestAccount($_POST["userid"])) {
                $min_password_length = $this->registry->get("MIN_PASSWORD_LENGTH");
                if (strlen((string) $_POST["new_password"]) < $min_password_length) {
                    $password = false;
                } else {
                    $password = $this->createPassword((string) $_POST["new_password"]);
                }

                $username = $this->createUsername($_POST["username"]);
                $user_model = new \Application\Model\User();
                $user_model->editUser(
                        (int) $_POST["user_id"], $username, $_POST["shown_name"], $password, $_POST["email"]
                );
            }
        } else {
            throw new \RightException("admin_edit_user");
        }
    }

}