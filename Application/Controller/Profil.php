<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace Application\Controller;

use JackAssPHP\Helper\HtmlPage as JaHt;

/**
 * Displays the Profile page
 *
 * @category Controller
 * @package  MLM
 * @author   Mesa <daniel.langemann@gmx.de>
 */
class Profil extends \JackAssPHP\Core\Controller
{

    protected $meta_data = null;

    /**
     * constructor
     */
    public function __construct ()
    {
        $this->loadSystem();
    }

    /**
     * Displays the Impressum page
     *
     * @return void
     */
    public function index ( $args = null)
    {
        $rights = \Factory::getRights();

        if ( $rights->hasRight("profil_view")
            or $rights->hasRight("profil_change_username_and_password")
        ) {

            $user = \Factory::getUser();
            $lang = \Factory::getTranslate();

            $template = \Factory::getView();
            $page = \Factory::getHtmlResponse();
            $page->setTitle($lang->translate("PROFIL") . " - " . $this->registry->get("GUILD_NAME"));
            $page->addStyleSheet($template->load("Profil/css"));
            $page->setTemplate("Layout.html");

            $page->addToContent($template->reset()->load("Profil/content"));
        } else {
            $html = \Factory::getView();
            echo $html->load("Error/noRights");
        }
    }

    public function changeData ( $args = null )
    {
        $rights = \Factory::getRights();
        $jsonResponse = new \JackAssPHP\Core\ResponseJson();
        $jsonResponse->executed = false;

        if ( $rights->hasRight("profil_change_username_and_password") ) {
            $lang = \Factory::getTranslate();
            switch ( $args["data"] ) {
                case "name":
                    if (isset($_POST["name"]) && strlen($_POST["name"]) > 0) {
                        $user = \Factory::getUser();
                        $name = $_POST["name"];
                        $response = $user->setName($name ,$jsonResponse) ;
                        $jsonResponse->executed = $response->status;
                        $jsonResponse->name     = $response->name;

                        if ( $response->name_exists) {
                            $jsonResponse->message = $lang->translate("NAME_ALREADY_EXISTS");
                        }
                    } else {
                        $jsonResponse->message = $lang->translate("INPUT_WAS_EMPTY");
                    }
                break;
                case "email":
                    if (isset($_POST["email"]) && strlen($_POST["email"]) > 0) {
                        $user = \Factory::getUser();
                        $response = $user->setEmail($_POST["email"]);
                        $jsonResponse->executed = $response->status;
                        $jsonResponse->email    = $response->email;
                        if ( $jsonResponse->executed === false ) {
                            $jsonResponse->message = $lang->translate("EMAIL_IS_NOT_VALID");
                        }

                    } else {
                        $jsonResponse->message = $lang->translate("INPUT_WAS_EMPTY");
                    }
                break;
                case "login":

                    $user = \Factory::getUser();

                    if (
                        !isset($_POST["new_password1"])
                        || strlen($_POST["new_password1"]) < $this->registry->get("MIN_PASSWORD_LENGTH")
                    ) {
                        $jsonResponse->message = $lang->translate("PASSWORD_TO_SHORT");
                        return $jsonResponse;
                    }

                    if ( !isset($_POST["new_password2"])
                        || $_POST["new_password1"] !== $_POST["new_password2"]
                    ) {
                        $jsonResponse->message = $lang->translate("PASSWORDS_NOT_MATCH");
                        return $jsonResponse;
                    }

                    if ( !isset($_POST["old_password"]) or strlen($_POST["old_password"]) == 0
                    ) {
                        $jsonResponse->message = $lang->translate("INPUT_WAS_EMPTY");
                        return $jsonResponse;
                    }

                    $jsonResponse->verified = $user->login($user->getLoginName(), $_POST["old_password"]);

                    if ( $jsonResponse->verified ) {
                        $loginResponse = $user->setLoginName($_POST["login"]);
                        $passwordResponse = $user->setPassword($_POST["new_password1"]);

                        $jsonResponse->login_status = $loginResponse->status;
                        $jsonResponse->password_status = $passwordResponse->status;

                        if ( $jsonResponse->login_status && $jsonResponse->password_status ) {
                            $jsonResponse->executed = true;
                        } else {
                            /**
                             * alles wieder Rückgängig machen.
                             */
                            if (!$jsonResponse->login_status) {
                                $user->setLoginName( $user->getLoginName() );
                            }

                            if (!$jsonResponse->password_status) {
                                $user->setPassword( $_POST["old_password"]);
                            }
                        }
                    } else {
                        $jsonResponse->message = $lang->translate("WRONG_PASSWORD");
                    }
                break;
                default:
                    $jsonResponse->executed = false;
            }
        }
    }
}