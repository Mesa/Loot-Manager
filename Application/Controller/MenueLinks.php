<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace Application\Controller;

use JackAssPHP\Helper\HtmlPage as JaHt;

class MenueLinks extends \JackAssPHP\Core\Controller
{

    /**
     * Constructor
     */
    public function __construct ()
    {
        $this->loadSystem();
        $this->rights = \Factory::getRights();
    }

    public function index ( $args = null )
    {
        if ( $this->rights->hasRight("edit_menue_link")
        ) {

            $lang = \Factory::getTranslate();
            $template = \Factory::getView();
            $page = \Factory::getHtmlResponse();
            $page->setTemplate("AdminLayout.html");
            $page->addStyleSheet($template->load("MenueLinks/css"));
            $page->setTitle($this->registry->get("SYSTEM_NAME") . " - " .$lang->translate("MENUE-LINKS"));
            $page->addJavascript($template->load("MenueLinks/admin.js"));

            $dao_link = new \Application\Model\MenueLinks();
            $dao_rights = new \Application\Model\Rights();
            $all_rights = $dao_rights->getAllRights();

            $template->right_list = $all_rights;
            $template->link_list = $dao_link->getAllLinks();
            $page->addToContent($template->load("MenueLinks/main"));
        } else {
            /**
             * @todo throw Exception
             */
        }
    }

    public function editRight ( $args = null )
    {
        if ( $this->rights->hasRight("edit_menue_link") ) {
            $id = (int) $args["item"];
            $rightId = (int) $args["right"];

            if ( $rightId === 0 ) {
                $rightId = null;
            } else {
                $dao_rights = new \Application\Model\Rights();
                $right = $dao_rights->byId($rightId);
            }

            $dao_link = new \Application\Model\MenueLinks();
            $dao_link->updateRight($id, $right["name"]);
        } else {
            /**
             * @todo throw Exception
             */
        }
    }

    public function editOrder ( $args = null )
    {
        if ( $this->rights->hasRight("edit_menue_link") ) {

            $dao_links = new \Application\Model\MenueLinks();
            $dao_links->updateOrder($args["Id"], $args["newPosition"]);
        } else {
            $template = \Factory::getView();
            echo $template->load("Error/noRights");
        }
    }

    public function editName ( $args = null )
    {
        if ( $this->rights->hasRight("edit_menue_link") ) {
            $name = htmlentities(strip_tags(trim($_POST["value"])),ENT_QUOTES, 'UTF-8');
            $id = (int) $args["Id"];

            $dao_links = new \Application\Model\MenueLinks();
            $dao_links->updateName($id, $name);
        } else {
            /**
             * @todo addException
             */
        }
    }

    public function editPath ( $args = null )
    {
        if ( $this->rights->hasRight("edit_menue_link") and isset($args["Id"])) {
            $id = (int) $args["Id"];
            $path = trim($_POST["value"]);

            $dao_links = new \Application\Model\MenueLinks();
            $dao_links->updatePath($id, $path);
        } else {
            $template = \Factory::getView();
            echo $template->load("Error/noRights");
        }
    }

    public function deleteItem ( $args = null )
    {
        if ( $this->rights->hasRight("edit_menue_link") ) {
            $dao_link = new \Application\Model\MenueLinks();
            $dao_link->remove($args["id"]);
        } else {
            $template = \Factory::getView();
            echo $template->load("Error/noRights");
        }
    }

    public function addItem ( $args = null )
    {
        if ( $this->rights->hasRight("edit_menue_link") ) {

            $name = htmlentities($_POST["name"]);
            $path = $_POST["path"];
            $dao_link = new \Application\Model\MenueLinks();
            $dao_link->add($name, $path);
        } else {
            $template = \Factory::getView();
            echo $template->load("Error/noRights");
        }
    }

}