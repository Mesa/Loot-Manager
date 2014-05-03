<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace Application\Controller;

use JackAssPHP\Helper\FormToken as JaFo;

class Login extends \JackAssPHP\Core\Controller
{
    public function __construct ()
    {
        $this->loadSystem();
    }

    public function index ( $args = null )
    {
        $user = \Factory::getUser();
        $blacklist = \Factory::getBlackList();


        if ( $blacklist->isBlocked() === false
            && $user->isLoggedIn() === false
        ) {

            $html = \Factory::getView();
            /**
             * @todo remove Singleton pattern
             */
            $form_token = JaFo::getInstance();
            $html->form_token = $form_token->getToken();
            $_SESSION["username_input_salt"] = $form_token->generateSalt(10);
            $html->username_input = $_SESSION["username_input_salt"];
            $_SESSION["password_input_salt"] = $form_token->generateSalt(10);
            $html->password_input = $_SESSION["password_input_salt"];
            $html->login_try = $blacklist->getLoginTry();

            if (isset($args["small"]) && $args["small"] == 1) {
                $html->inline = true;
            }
            echo $html->load("Login/Login_Window");

        } elseif ( $blacklist->isBlocked() ) {
            $view = \Factory::getView();
            echo $view->load("blacklisted_error_msg");
        }
    }

    public function checkLogin ( $arg )
    {
        $user       = \Factory::getUser();
        /**
         * @todo remove Singleton pattern
         */
        $form_token = JaFo::getInstance();
        $blacklist  = \Factory::getBlackList();
        $registry   = \Factory::getRegistry();

        if ($form_token->checkToken($arg["token"]) === true
            && $user->isLoggedIn() == false
        ) {

            $username = trim($_POST[$_SESSION["username_input_salt"]]);
            $password = trim($_POST[$_SESSION["password_input_salt"]]);

            if (isset($_POST["useCookie"])
                && $_POST["useCookie"] == "on"
            ) {
                $remember_me = true;
            } else {
                $remember_me = false;
            }

            $result = $user->login($username, $password, $remember_me);

            if ($result === false) {

                $blacklist->addPenalty();
                header(
                    "Location:"
                        . $registry->get("WEB_ROOT")
                        . $registry->get("LOGIN_REDIRECT_PATH")
                );
            } else {
                $blacklist->clearIp();
                header("Location:" . $registry->get("WEB_ROOT"));
            }
        } else {
            header("Location:" . $registry->get("WEB_ROOT"));
        }
    }

    public function logout ()
    {
        $user = \Factory::getUser();
        $user->logout();
        header("Location:" . $this->registry->get("WEB_ROOT") . "");
    }

}