<?php

namespace JackAssPHP\Core;

class Captcha
{

    protected $captcha_obj = null;
    protected $session_name = "captcha_verified";
    protected $referrer_cookie = "captcha_referrer";
    protected $redirect_path = null;
    protected $verifyPath = null;

    public function __construct ()
    {

        if (!isset($_SESSION[$this->session_name])) {
            $_SESSION[$this->session_name] = false;
        }

        $registry = \Factory::getRegistry();

        $this->redirect_path
            = $registry->get("WEB_ROOT") .
            $registry->get("CAPTCHA_REDIRECT_PATH");

        $this->verifyPath
            = $registry->get("WEB_ROOT") .
            $registry->get("CAPTCHA_VERIFY_PATH");

        if ($this->captcha_obj == null) {
            $captcha = $registry->get("CAPTCHA_CLASS");
            switch (strtolower($captcha)) {
            case "recaptcha":
                $this->captcha_obj = new \JackAssPHP\Library\ReCaptcha();
                $this->captcha_obj->setPublicKey($registry->get("RECAPTCHA_PUB_KEY"))->setPrivateKey($registry->get("RECAPTCHA_PRIV_KEY"));
                break;

            default:
            }
        }

        return $this->captcha_obj;
    }

    public function get_html ()
    {
        return $this->captcha_obj->get_html();
    }

    public function is_valid ()
    {
        $data = $this->captcha_obj->is_valid();

        if ($data->is_valid === true) {
            $_SESSION[$this->session_name] = true;
            return true;
        } else {
            return $data->error;
        }
    }

    /**
     * The user has verified with a captcha?
     *
     * @return [Bool] true if he has
     */
    public function hasVerified ()
    {
        if ($_SESSION[$this->session_name] === true) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Redirect user to the captcha URL
     */
    public function redirect ()
    {
        $registry = \Factory::getRegistry();
        $_SESSION[$this->referrer_cookie] = $registry->get("REQUEST_PATH");
        header("Location: " . $this->redirect_path);
    }

    /**
     * Return the path to verify the captcha
     *
     * @return [String]
     */
    public function getVerifyPath ()
    {
        return $this->verifyPath;
    }

    public function getReferrer ()
    {
        return $_SESSION[$this->referrer_cookie];
    }

}