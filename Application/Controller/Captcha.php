<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace Application\Controller;

class Captcha extends \JackAssPHP\Core\Controller
{

    public function __construct()
    {
        $this->loadSystem();
    }

    public function index ()
    {
        $captcha = new \JackAssPHP\Core\Captcha();

        if ( $captcha->hasVerified() === false) {

            $html = \Factory::getHtmlResponse();
            $html->setTemplate("Layout.html");
            $template = \Factory::getView();
            $template->captcha_html = $captcha->get_html();
            $template->form_action = $captcha->getVerifyPath();
            $html->addStyleSheet($template->load("Captcha/css"));
            $html->addToContent($template->load("Captcha/form"));
            $html->setTitle($this->registry->get("GUILD_NAME") . " - Captcha");
        }
    }

    public function verify ()
    {
        $captcha = new \JackAssPHP\Core\Captcha();

        if ( $captcha->hasVerified() === false) {
            $data = $captcha->is_valid();

            if ( $data === true ) {
                header("Location: " . $this->registry->get("WEB_ROOT") . $captcha->getReferrer());
            } else {
                header("Location: ". $this->registry->get("WEB_ROOT") . $this->registry->get("CAPTCHA_REDIRECT_PATH"));
            }
        } else {
            header("Location: " . $this->registry->get("WEB_ROOT") . $captcha->getReferrer());
        }
    }

}