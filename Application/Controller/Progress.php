<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace Application\Controller;

class Progress extends \JackAssPHP\Core\Controller
{

    /**
     * constructor
     */
    public function __construct ()
    {
        parent::loadSystem();
        $this->rights = \Factory::getRights();
    }

    /**
     * Default page
     *
     * @param type $args Request Args
     *
     * @return void
     */
    public function index ( $args = null )
    {
        $lang = \Factory::getTranslate();
        $header = \Factory::getView();
        $content = \Factory::getView();
        $template = \Factory::getView();

        $page = \Factory::getHtmlResponse();
        $page->setTemplate("Layout.html");
        $page->setTitle($lang->Translate("PROGRESS") . " - " . $this->registry->get("GUILD_NAME"));
        $page->addStyleSheet($template->load("Progress/css"));

        $dungeons = new \Application\Model\Dungeon();
        $encounter = new \Application\Model\Encounter();

        $content->encounter = $encounter->getAllEncounter();
        $content->activeDungeons = $dungeons->getActiveDungeons();
        $content->disabledDungeons = $dungeons->getDisabledDungeons();

        if ( $this->rights->hasRight("progress_edit") ) {
            $page->addJavascript($template->load("Progress/admin.js"));
        }

        $page->addToContent($content->load("Progress/content"));
    }

    /**
     * create new Dungeon
     */
    public function createDungeon ()
    {
        if ( $this->rights->hasRight("progress_edit") ) {
            $model = new \Application\Model\Dungeon();
            $model->createDungeon($_POST["name"]);
        }
    }

    /**
     * remove Encounter from Dungeon
     */
//    public function removeEncounter ()
//    {
//        if ( $this->rights->hasRight("progress_edit") ) {
//
//        }
//    }

    /**
     * add Encounter to Dungeon
     */
    public function addEncounter ()
    {
        if ( $this->rights->hasRight("progress_edit") ) {
            $model = new \Application\Model\Encounter();
            $encounter_id = (int) $_POST["encId"];
            $dungeon_id = (int) $_POST["dungId"];
            $model->addToDungeon($encounter_id, $dungeon_id);
        }
    }

    /**
     * create encounter in DB and return the new Encounter Id
     */
    public function createEncounter ()
    {
        if ( $this->rights->hasRight("progress_edit")
                and isset($_POST["name"]) ) {
            $name = $_POST["name"];
            $model = new \Application\Model\Encounter();
            $result = $model->createEncounter($name);

            echo $result["Id"];
        }
    }

    /**
     * edit Encounter Name and Status
     */
    public function editEncounter ()
    {
        if ( $this->rights->hasRight("progress_edit") ) {

            $model = new \Application\Model\Encounter();
            if ( isset($_POST["cleared"]) and isset($_POST["encId"]) ) {

                $status = (int) $_POST["cleared"];
                $id = (int) $_POST["encId"];
                $model->changeStatus($id, $status);
            } elseif ( isset($_POST["encId"]) and isset($_POST["encName"]) ) {

                $name = $_POST["encName"];
                $id = (int) $_POST["encId"];
                $model->changeName($id, $name);
            } elseif ( isset($_POST["newPos"])
                    and isset($_POST["encId"])
                    and isset($_POST["oldPos"])
                    and isset($_POST["dungId"]) ) {
                $id = (int) $_POST["encId"];
                $newPosition = (int) $_POST["newPos"];
                $dungeonId = (int) $_POST["dungId"];
                $oldPosition = (int) $_POST["oldPos"];

                $model->changeSortOrder($id, $dungeonId, $newPosition, $oldPosition);
            }
        }
    }

    public function editDungeon ()
    {
        if ( $this->rights->hasRight("progress_edit") ) {

            $model = new \Application\Model\Dungeon();
            if ( isset($_POST["dungId"]) and isset($_POST["dungName"]) ) {

                $id = (int) $_POST["dungId"];
                $name = $_POST["dungName"];
                $model->editDungeonName($id, $name);
            } elseif ( isset($_POST["dungId"]) and isset($_POST["dungStatus"]) ) {

                $id = (int) $_POST["dungId"];
                $status = (int) $_POST["dungStatus"];
                $model->setDungeonStatus($id, $status);
            } elseif ( isset($_POST["newPos"]) and isset($_POST["dungId"]) ) {

                $dungeonId = (int) $_POST["dungId"];
                $newPosition = (int) $_POST["newPos"];

                $model->changeOrder($dungeonId, $newPosition);
            }
        }
    }

    public function clearTrash ()
    {
        if ( $this->rights->hasRight("progress_edit") ) {
            $dungeon = new \Application\Model\Dungeon();
            $dungeon->clearAllDisabled();
            $encounter = new \Application\Model\Encounter();
            $encounter->deleteUnusedEncounter();
        }
    }

}