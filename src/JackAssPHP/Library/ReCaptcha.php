<?php

namespace JackAssPHP\Library;

require __DIR__ . DS . 'recaptcha-php-1.11/recaptchalib.php';

class ReCaptcha
{

    protected $private_key = null;
    protected $public_key = null;

    public function construct ()
    {

    }

    public function setPrivateKey ( $privateKey )
    {
        $this->private_key = $privateKey;
        return $this;
    }

    public function setPublicKey ( $publicKey )
    {
        $this->public_key = $publicKey;
        return $this;
    }

    public function get_html( )
    {
        return \recaptcha_get_html($this->public_key);
    }

    public function is_valid()
    {
        if ( isset($_POST["recaptcha_response_field"])) {
            $resp = recaptcha_check_answer(
                $this->private_key,
                $_SERVER["REMOTE_ADDR"],
                $_POST["recaptcha_challenge_field"],
                $_POST["recaptcha_response_field"]
            );

            return $resp;
        }
    }

}