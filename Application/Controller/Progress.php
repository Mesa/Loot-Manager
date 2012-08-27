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
        if ($this->rights->hasRight("SHOW_PROGRESS") || $this->rights->hasRight("PROGRESS_EDIT") ) {
            $game_name = "rift";
            $template = \Factory::getView();
            $lang     = \Factory::getTranslate();

            $page     = \Factory::getHtmlResponse();
            $page->setTemplate("Layout.html");
            $page->setTitle($lang->translate("PROGRESS") ." - " . $this->registry->get("GUILD_NAME"));
            $page->addStyleSheet($template->load("Progress/css"));

            $progress_dao = new \Application\Model\Progress();
            $data = $progress_dao->getData($game_name);
            $template->data = $data;
            $page->addToContent($template->load("Progress/content"));
        } else {
            throw new \RightException("SHOW_PROGRESS");
        }
    }

    public function switchState ( $args = null )
    {
        /**
         * @todo rename right to edit_progress
         */
        if ( $this->rights->hasRight("PROGRESS_EDIT") ) {
            $progress_dao = new \Application\Model\Progress();
            $name = $args["name"];
            
            $response = new \JackAssPHP\Core\ResponseJson();
            $response->executed = false;
            
            if (strlen($name) > 1) {
                $response->executed = $progress_dao->toggleStatus($name);
            } else {
                $response->error_msg = "Name not found";
            }
        } else {
            throw new \RightException("PROGRESS_EDIT");
        }
    }
}