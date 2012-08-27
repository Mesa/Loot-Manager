<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace Application\Controller;

use Application\Model\Chars as ApPl;
use Application\Model\Event as EventModel;
//use JackAssPHP\Helper\HtmlPage as JaHt;

class Loot extends \JackAssPHP\Core\Controller
{

    public function __construct ()
    {
        $this->loadSystem();
    }

    public function index ( $args = null )
    {
        $lang = \Factory::getTranslate();
        $player_list = new ApPl();
        $event_model = new EventModel();
        $rights = \Factory::getRights();

        $user = \Factory::getUser();
        if (!isset($args["event_id"])) {
            $event_id = $event_model->getFirstEventId();
        } else {
            $event_id = (int) $args["event_id"];
        }

        if ($event_id == false) {
            /**
             * If no event exists, display a empty page, to avoid a loop
             */
        } else {

            if ($player_list->eventExists($event_id) === false) {
                header("Location: " . $this->registry->get("WEB_ROOT") . "loot/");
                die();
            }

            $layout = \Factory::getView();
            $content = \Factory::getView();
            $list = \Factory::getView();

            $page       = \Factory::getHtmlResponse();
            $template   = \Factory::getView();

            $page->setTemplate("Layout.html");
            $page->addStyleSheet($template->load("Loot/css"));
            $page->setTitle($lang->Translate("LOOT_LIST") . " - " . $this->registry->get("GUILD_NAME"));

            $page->addJavascript($template->load("Loot/default.js"));

            $admin_js = \Factory::getView();

            if (true === $user->isLoggedIn()) {
                $layout->is_admin = $user->isLoggedIn();
                /**
                 * Die Admin Javascript Datei laden und als Text ins Dokument
                 * einfügen
                 */
                $admin_js->loot_path = "loot/";
                $item_list = new \Application\Model\LootLog();
                $admin_js->item_list = $item_list->getDescList("suicide");
                $admin_js->moved_desc = $item_list->getDescList("char_moved");
                $admin_js->class_list = $player_list->getClassList();
                $content->missing_player = $player_list->playerNotInEvent($event_id);
                $content->is_admin = true;

                $page->addJavascript($admin_js->load("Loot/admin.js"));
            } else {
                $layout->is_admin = false;
                $content->is_admin = false;
            }
            /**
             * Default JS magic to handle class select for the List
             */

            $list->list         = $player_list->getPlayerList($event_id);
            $list->event_name   = $player_list->getEventName($event_id);
            $list->event_id     = $event_id;

            $content->filter_list = $player_list->getClassList();
            $list->event_id       = $event_id;
            $content->player_list = $list->load("Loot/total_player_list");
            $content->event_list  = $player_list->getEventList();

            $page->addToContent($content->load("Loot/content"));
        }
    }

    /**
     *
     * @param type $args
     */
    public function player_menu ( $args )
    {
        if (!isset($args["event_id"])) {
            $event_id = 1;
        } else {
            $event_id = (int) $args["event_id"];
        }

        $rights = \Factory::getRights();
        if ($rights->hasRight("loot_view_admin_menu")) {

            $player_list = new ApPl();
            $layout = \Factory::getView();
            $layout->rights = $rights;
            $layout->event_id = $event_id;
            echo $layout->load("Loot/admin_player_menu");
        }
    }

    public function create_player_menu ()
    {
        $rights = \Factory::getRights();
        $user = \Factory::getUser();
        if ($rights->hasRight("loot_view_admin_menu")) {
            $player_list = new ApPl();
            $layout = \Factory::getView();
            $layout->json_klass_tags = $player_list->getClassList();
            echo $layout->load("Loot/admin_create_player_menu");
        }
    }

    public function save_new_player ()
    {
        $rights = \Factory::getRights();
        if ($rights->hasRight("loot_create_char")) {
            if (isset($_POST["name"]) and isset($_POST["type"])) {

                $char_name = htmlentities($_POST["name"], ENT_QUOTES, 'UTF-8');
                $char_class = htmlentities($_POST["type"], ENT_QUOTES, 'UTF-8');
                $player_list = new ApPl();
                $player_list->addPlayer($char_name, $char_class);
            }
        }
    }

    public function movePlayer ( $args )
    {
        $rights = \Factory::getRights();
        $user = \Factory::getUser();

        if ($rights->hasRight("loot_move_char")) {

            $player_id = (int) $_POST["player_id"];
            $event_id = (int) $_POST["event_id"];
            $description = $_POST["description"];
            $FromPosition = (int) $_POST["from_position"];
            $new_position = (int) $_POST["new_position"];

            $loot_log = new \Application\Model\LootLog();
            $player_list = new ApPl();

            if ($FromPosition !== $new_position) {
                $loot_log->addEntry($event_id, $player_id, $FromPosition, $new_position, $user->getUserId(), $description, "char_moved");
                $player_list->movePlayerToPosition($player_id, $event_id, $new_position);
            }
        }
    }

    public function killKing ( $args )
    {
        $rights = \Factory::getRights();
        $user = \Factory::getUser();

        if ($rights->hasRight("loot_move_char")) {
            $player_id = (int) $_POST["player_id"];
            $event_id = (int) $_POST["event_id"];
            $description = $_POST["description"];
            $FromPosition = (int) $_POST["fromPosition"];
            $newPosition = (int) $_POST["new_position"];

            $loot_log = new \Application\Model\LootLog();

            $player_list = new ApPl();

            $loot_log->addEntry($event_id, $player_id, $FromPosition, $newPosition, $user->getUserId(), $description, "suicide");
            $player_list->movePlayerToPosition($player_id, $event_id, $new_position);
        }
    }

    public function addPlayerToEvent ( $args )
    {
        $user = \Factory::getUser();
        $rights = \Factory::getRights();
        if ($rights->hasRight("loot_add_char_to_event")) {

            $loot_log = new \Application\Model\LootLog();
            $char_id = (int) $args["char_id"];
            $event_id = (int) $args["event_id"];

            $player_list = new ApPl();
            $player_list->addPlayerToEvent($char_id, $event_id);

            $loot_log->addEntry($event_id, $char_id, 0, 0, $user->getUserId(), "", "char_added_to_event");
        }
    }

    public function dropPlayerfromEvent ( $args )
    {
        $user = \Factory::getUser();
        $rights = \Factory::getRights();

        if ($rights->hasRight("loot_remove_char_from_event")) {

            $row_id = (int) $args["row_id"];
            $player_list = new ApPl();
            $data = $player_list->getPlayerIdByRow($row_id);
            if ($data !== false) {

                $player_list->dropPlayerfromEvent($row_id);
                $loot_log = new \Application\Model\LootLog();
                $loot_log->addEntry($data["EventId"], $data["CharId"], $data["Position"], "NULL", $user->getUserId(), "", "char_removed_from_list");
            }
        }
    }

    public function editPlayer ()
    {
        $user = \Factory::getUser();
        $rights = \Factory::getRights();

        if ($rights->hasRight("loot_edit_char")) {
            $char_id = $_POST["char_id"];
            $char_name = $_POST["char_name"];
            $char_class = $_POST["char_class"];
            $char_description = filter_var($_POST["char_description"], FILTER_SANITIZE_STRING);

            $data_model = new ApPl();

            $log_message = \Factory::getView();
            $log_message->moderator = $user->getName();
            $log_message->char_name = $char_name;

            $data_model->editPlayer($char_id, $char_name, $char_class, $char_description);
        }
    }

    public function deletePlayer ( $args )
    {
        $char_id = (int) $args["char_id"];
        $rights = \Factory::getRights();
        $user = \Factory::getUser();

        if ($rights->hasRight("loot_delete_char")) {
            $player = new ApPl();

            $log_message = \Factory::getView();
            $log_message->char_name = $player->getCharName($char_id);
            $log_message->moderator = $user->getName();
            $player->deletePlayer($char_id);
        }
    }

    public function createEvent ( $args )
    {
        $rights = \Factory::getRights();
        if ($rights->hasRight("LOOT_CREATE_EVENT") and
                isset($_POST["event_name"])) {
            $event_name = $_POST["event_name"];
            $event_model = new \Application\Model\Event();
            $event_model->addEvent($event_name);
        }
    }

    public function deleteEvent ( $args )
    {
        $rights = \Factory::getRights();
        if ($rights->hasRight("LOOT_DELETE_EVENT") and
                isset($_POST["event_id"])) {
            $event_id = (int) $_POST["event_id"];

            $event_model = new \Application\Model\Event();
            $event_model->deleteEvent($event_id);
        }
    }

    public function editEvent ( $args )
    {
        $rights = \Factory::getRights();
        if ($rights->hasRight("LOOT_EDIT_EVENT") and
                isset($_POST["event_name"]) and
                isset($_POST["event_id"])) {

            $event_name = $_POST["event_name"];
            $event_id = $_POST["event_id"];

            $event_model = new \Application\Model\Event();
            $event_model->editEvent($event_id, $event_name);
        }
    }

    public function getEventLog ( $args )
    {
        $event_id = (int) $args["event_id"];
        $loot_log = new \Application\Model\LootLog();
        $eventDao = new \Application\Model\Event();
        $charDao = new \Application\Model\Chars();
        $user = \Factory::getUser();
        $lang = \Factory::getTranslate();
        $html = \Factory::getView();
        $event_log_limit = (int) $_GET["item_count"];

        $log_args["suicide"] = ($_GET["suicide"] == "checked" ) ? "suicide" : "";
        $log_args["moved"] = ($_GET["moved"] == "checked") ? "char_moved" : "";
        $log_args["added"] = ($_GET["added"] == "checked") ? "char_added_to_event" : "";
        $log_args["removed"] = ($_GET["removed"] == "checked") ? "char_removed_from_list" : "";

        $icons = array(
            "suicide" => "coins.png",
            "char_added_to_event" => "group_add.png",
            "char_removed_from_list" => "group_delete.png",
            "char_moved" => "arrow_switch.png"
        );

        $actions = array(
            "suicide" => $lang->translate("Item_recieved"),
            "char_added_to_event" => $lang->translate("Char_added_to_event"),
            "char_removed_from_list" => $lang->translate("Char_removed_from_event"),
            "char_moved" => $lang->translate("Char_moved")
        );

        $html->event_log_limit = $event_log_limit;
        $html->user = $user;
        $html->charDao = $charDao;
        $html->action_names = $actions;
        $html->icon = $icons;
        $html->event = $eventDao;
        $html->list = $loot_log->getEventLog($event_id, $log_args, $event_log_limit);
        $html->event_log_limit = $event_log_limit;
        echo $html->load("Loot/event_log");
    }

    public function getCharLog ( $args )
    {
        $char_id = (int) $args["char_id"];
        $event_id = (int) $_GET["event_id"];
        $lang = \Factory::getTranslate();
        $loot_log = new \Application\Model\LootLog();
        $char_log_limit = (int) $_GET["item_count"];

        $log_args["suicide"] = (isset($_GET["suicide"]) && $_GET["suicide"] == "checked" ) ? "suicide" : "";
        $log_args["moved"] = (isset($_GET["moved"]) && $_GET["moved"] == "checked") ? "char_moved" : "";
        $log_args["added"] = (isset($_GET["added"]) && $_GET["added"] == "checked") ? "char_added_to_event" : "";
        $log_args["removed"] = (isset($_GET["removed"]) && $_GET["removed"] == "checked") ? "char_removed_from_list" : "";

        $user = \Factory::getUser();
        $eventDao = new \Application\Model\Event();
        $html = \Factory::getView();
        $icons = array(
            "suicide" => "coins.png",
            "char_added_to_event" => "group_add.png",
            "char_removed_from_list" => "group_delete.png",
            "char_moved" => "arrow_switch.png"
        );
        $html->icon = $icons;

        $actions = array(
            "suicide" => $lang->translate("Item_recieved"),
            "char_added_to_event" => $lang->translate("Char_added_to_event"),
            "char_removed_from_list" => $lang->translate("Char_removed_from_event"),
            "char_moved" => $lang->translate("Char_moved")
        );
        $html->action_names = $actions;

        $html->user = $user;
        $html->event = $eventDao;
        $html->char_id = $char_id;
        $html->event_id = $event_id;
        $html->char_log_limit = $char_log_limit;
        $html->list = $loot_log->getCharLog($char_id, $event_id, $log_args, $char_log_limit);
        $html->item_count = $loot_log->getItemCount($char_id, $event_id);

        $join_date = $loot_log->getEventJoinDate($char_id, $event_id);
        if ($join_date > 1) {
            $roundTime = new \JackAssPHP\Helper\RoundTime();
            $roundTime->getDifference($join_date, time());
            $html->join_date = $roundTime->getString();
        } else {
            $html->join_date = "";
        }
        echo $html->load("Loot/char_log");
    }

}