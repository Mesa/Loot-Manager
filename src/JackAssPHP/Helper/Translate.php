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

use JackAssPHP\Core\MySQLDB as JaMy;

/**
 * __
 *
 * @category Helper
 * @package  JackAssPHP
 * @author   Mesa <daniel.langemann@gmx.de>
 */
class Translate
{

    /**
     * Fallback lang to display
     *
     * @var [String]
     */
    protected $default_lang = "en";
    protected $user_lang = null;
    protected $data = array( );
    protected $table_name = "translation";

    public function __construct ( $db_connection, $registry )
    {
        $this->db = $db_connection;
        $this->registry = $registry;

        if ( false !== $this->db ) {

            $this->default_lang = $this->registry->get("DEFAULT_LANG");

            if (isset($_COOKIE["user-lang"])) {
                $this->user_lang = strtolower($_COOKIE["user-lang"]);
            } else {
                preg_match('/^(\w\w)/', $_SERVER["HTTP_ACCEPT_LANGUAGE"], $user_lang);

                if ( isset($user_lang[0]) and strlen($user_lang[0]) > 1 ) {
                    $this->user_lang = strtolower($user_lang[0]);
//                    setcookie("user-lang", $this->user_lang);
                } else {
                    $this->user_lang = strtolower($this->default_lang);
                }
            }
            $this->loadDataFromFile();
        }
    }

    public function getUserLang ( )
    {
        return strtoupper($this->user_lang);
    }

    protected function loadDataFromFile ()
    {
        switch ($this->user_lang) {
            case "de":
                $file = $this->registry->get("CONFIG_PATH") . "lang_de.xml";
                $lang = "de";
                break;
            default:
                $file = $this->registry->get("CONFIG_PATH") . "lang_en.xml";
                $lang = "en";
                break;
        }

        if( file_exists($file)) {
            $data = simplexml_load_file($file);

            foreach( $data as $key => $value) {
                $this->data[$lang][strtoupper($value->key)] = (string) $value->string;
            }
        }
    }

    /**
     * Translate a Key to an language choosen by the user setting in his browser
     * Fallback language is english.
     *
     * @param type $key  Key to translate
     * @param type $lang To use a prefered language
     *
     * @return [String] Translated string
     */
    public function translate ( $key, $lang = null )
    {
        $string = strtoupper($key);

        if ( $lang == null ) {
            $lang = $this->user_lang;
        }

        if ( isset($this->data[strtolower($lang)][$string]) ) {
            return $this->data[strtolower($lang)][$string];
        } else {
            if ( isset($this->data[strtolower($this->default_lang)][$string]) ) {
                return $this->data[strtolower($this->default_lang)][$string];
            } else {
                return "<span style=\"color: red\">" . $string . "</span>";
            }
        }
    }

}