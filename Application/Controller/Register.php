<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace Application\Controller;

class Register extends \JackAssPHP\Core\Controller
{

    public function __construct ()
    {
        $this->loadSystem();
    }

    public function index ()
    {
        $rights = \Factory::getRights();
        /**
         * Alle Einträge die älter als 24 Std sind löschen.
         */
        $register_dao = new \Application\Model\Register();
        $register_dao->dumpOld();
        $page = \Factory::getHtmlResponse();
        $page->setTemplate("Layout.html");
        $template = \Factory::getView();
        if ( isset($_SESSION["registered"]) and $_SESSION["registered"] == true ) {
            $page->setTitle($this->registry->get("GUILD_NAME") . " - Already Registered");
            $page->addToContent($template->load("Register/alreadyRegistered"));
        } elseif ($rights->hasRight("REGISTER")) {

            $captcha = new \JackAssPHP\Core\Captcha();
            if ($captcha->hasVerified()) {

                $tokenObj = \JackAssPHP\Helper\FormToken::getInstance();

                $page->addStyleSheet($template->load("Register/css"));
                $page->setTitle($this->registry->get("GUILD_NAME") . " - Register");
                $template->token = $tokenObj->getToken();
                $page->addToContent($template->load("Register/main"));
            } else {
                $captcha->redirect();
            }
        } else {
//            echo $template->load("Error/noRights");
        }
    }

    public function nameUnused ( $args = null )
    {
        $rights = \Factory::getRights();
        if ($rights->hasRight("REGISTER")) {
            $captcha = new \JackAssPHP\Core\Captcha();
            if ($captcha->hasVerified()) {
                $username = strip_tags($_POST["username"]);
                $user_dao = new \Application\Model\User();
                $result = $user_dao->usernameExists($username);
                if ($result > 0) {
                    echo json_encode(false);
                } else {
                    echo json_encode(true);
                }
            }
        } else {
            $template = \Factory::getView();
            echo $template->load("Error/noRights");
        }
    }

    public function emailUnused ( $args = null ) {
        $rights = \Factory::getRights();

        if ($rights->hasRight("REGISTER")) {
            $captcha = new \JackAssPHP\Core\Captcha();
            if ( $captcha->hasVerified()) {
                $email = strip_tags($_POST["email"]);
                $user_dao = new \Application\Model\User();
                $result = $user_dao->emailExists($email);
                if ($result > 0 ) {
                    echo json_encode(false);
                } else {
                    echo json_encode(true);
                }
            }
        }
    }

    public function save ( $args = null )
    {
        $rights = \Factory::getRights();
        $lang = \Factory::getTranslate();

        if ($rights->hasRight("REGISTER")) {
            $captcha = new \JackAssPHP\Core\Captcha();
            $tokenObj = \JackAssPHP\Helper\FormToken::getInstance();

            if (
                $captcha->hasVerified()
                and $tokenObj->checkToken($args["token"])
            ) {
                $response = new \JackAssPHP\Core\ResponseJson();
                $response->status   = true;
                $response->html     = "";
                $response->error_msg= array();

                $user   = \Factory::getUser();
                $filter = \Factory::getFilter();

                if ($filter->validateEmail($_POST["email"])) {
                    $email = $_POST["email"];
                } else {
                    $response->status = false;
                    $repsonse->error_msg[] = $lang->translate("EMAIL_IS_NOT_VALID");
                }

                $username_regex = $this->registry->get("VALIDATE_USERNAME_REGEX");
                if ( preg_match($username_regex,$_POST["name"])) {
                    $username = $user->encodeUsername($_POST["name"]);
                } else {
                    $repsonse->status = false;
                    $repsonse->error_msg[] = $lang->translate("USERNAME_IS_NOT_VALID");
                }

                $password_regex = $this->registry->get("VALIDATE_SPECIAL_CHAR_REGEX");
                if (
                    preg_match($password_regex, $_POST["password"])
                    and strlen($_POST["password"]) >= $this->registry->get("MIN_PASSWORD_LENGTH")
                ) {
                    $password = $user->hashPassword($_POST["password"]);
                } else {
                    $repsonse->status = false;
                    $repsonse->error_msg[][] = $lang->translate("PASSWORD_IS_NOT_VALID");
                }

                if ( $response->status === true ) {


                $token = hash('sha256',$tokenObj->generateSalt());

                $mail       = \Factory::getView();
                $mail->name = htmlentities($_POST["name"], ENT_QUOTES, 'UTF-8');
                $mail->link = "validate_email/" . $token . "/";

                $mail_obj   = \Factory::getEmail();
                $subject    = "Registrierung bei " . $this->registry->get("GUILD_NAME");
                $body       = $mail->load("Register/validate.mail");
                $repsonse->mail_status = $mail_obj->send(array($email),$subject, $body);

                if ($response->mail_status !== 1) {
                    $repsonse->status = false;
                    $response->error_msg[] = $lang->translate("ERROR_ON_EMAIL_SUBMITTING");
                }

                $register   = new \Application\Model\Register();
                $name       = htmlentities($_POST["name"], ENT_QUOTES, 'UTF-8');
                $response->db_response = $register->add($username, $name, $email, $password, $token);
                $_SESSION["registered"] = true;
                $html = \Factory::getView();
                $response->html = $html->load("Register/success");
                }
            }
        }
    }

    public function validate ( $args = null)
    {
        $rights = \Factory::getRights();
        if ($rights->hasRight("REGISTER")) {
            $key = $args["key"];
            $register_dao = new \Application\Model\Register();
            $register_dao->dumpOld();

            $page = \Factory::getHtmlResponse();
            $page->setTemplate("Layout.html");
            $template = \Factory::getView();
            $lang = \Factory::getTranslate();

            if ( $register_dao->keyExists($key) ) {
                $register_dao->moveToUser($key);
                $page->setTitle($this->registry->get("GUILD_NAME") . " - " .  $lang->translate("REGISTER") . " " .$lang->translate("YOU_ARE_NOW_REGISTERED"));
                $page->addToContent($template->load("Register/validated"));
            } else {
                /**
                 * Der Key existiert nicht oder existiert nicht mehr in der DB
                 */
                $page->setTitle($this->registry->get("GUILD_NAME") . " - " . $lang->translate("REGISTER"));
                $page->addToContent($template->load("Register/noKey"));
            }
        }
    }
}
