<?php

/**
 * Mesas Loot Manager
 * Copyright (C) 2011  Mesa <Daniel Langemann>
 *
 * Php version 5.3
 *
 * @category Helper
 * @package  Mesas_Loot_Manager
 * @author   Mesa <daniel.langemann@gmx.de>
 */

namespace JackAssPHP\Helper;

class FormToken
{

    protected $last_token = null;
    protected $active_token = null;
    protected $salt = null;

    public function getToken ()
    {
        if ($this->active_token == null) {
            $this->createToken();
        }
        return $this->active_token;
    }

    public static function getInstance ()
    {
        static $instance;
        if (!(isset($instance) and is_object($instance))) {
            $instance = new FormToken();
        }
        return $instance;
    }

    protected function __construct ()
    {
        if (isset($_SESSION['form_token']) and $_SESSION['form_token'] !== null) {
            $this->last_token = $_SESSION['form_token'];
        }
    }

    public function __destruct ()
    {
        $_SESSION["form_token"] = $this->active_token;
    }

    protected function createToken ()
    {
        $this->salt = $this->generateSalt();
        $this->active_token = hash('sha256', $this->salt);
    }

    public function checkToken ( $token )
    {
        if ($this->last_token == $token) {
            return true;
        } else {
            return false;
        }
    }

    public function generateSalt ( $max = 15 )
    {
        $characterList = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $i = 0;
        $salt = "";
        do {
            $salt .= $characterList{mt_rand(0, strlen($characterList) - 1)};
            $i++;
        } while ($i <= $max);
        return $salt;
    }

}

?>
