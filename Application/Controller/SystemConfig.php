<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace Application\Controller;

class SystemConfig extends \JackAssPHP\Core\Controller
{
    public function __construct ()
    {
        $this->loadSystem();
        $this->rights = \Factory::getRights();
    }

    public function index ( $args = null )
    {
        if ( $this->rights->hasRight("edit_config")) {
            $template = \Factory::getView();
            $page = \Factory::getHtmlResponse();
            $page->setTitle($this->registry->get("SYSTEM_NAME"));
            $page->addStyleSheet($template->load("SystemConfig/css"));
            $page->setTemplate("AdminLayout.html");

            $config_dao = new \Application\Model\Config();
            $template->config = $config_dao;

            $page->addToContent($template->load("SystemConfig/main"));
        } else {
            throw new \RightException("edit_config");
        }
    }

    public function edit ( $args = null )
    {
        if ( $this->rights->hasRight("edit_config") ) {
            if ( isset($args["name"]) and isset($_POST["value"])) {
                $conf_dao = new \Application\Model\Config();
                $name = strtoupper($args["name"]);
                $validated = false;

                $response["executed"] = false;
                $response["name"]  = $name;

                switch( $name ) {

                case "SMTP_FROMMAIL":
                    $value = filter_var(trim($_POST["value"]), FILTER_SANITIZE_EMAIL);
                    if (filter_var($value, FILTER_VALIDATE_EMAIL) !== false) {
                        $validated = true;
                    }
                break;

                case "SMTP_FROMNAME":
                    $value = filter_var(trim($_POST["value"]), FILTER_SANITIZE_STRING);
                    $validated = true;
                break;
                case "SMTP_PASSWORD":
                    $value = trim($_POST["value"]);
                    $validated = true;
                break;
                default:
                    $value = filter_var(trim($_POST["value"]), FILTER_SANITIZE_STRING);
                    $validated = true;
                }

                $response["value"] = $value;

                if ($validated === true) {
                    $conf_dao->updateValue($name, $value);
                    $response["executed"] = true;
                } else {
                    $response["error_msg"] = "Wurde nicht gespeichert";
                }

                echo json_encode($response);
            }
        } else {
            throw new \RightException("edit_config");
        }
    }
}