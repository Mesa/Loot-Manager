<?php

/**
 * Mesas Loot Manager
 * Copyright (C) 2011  Mesa <Daniel Langemann>
 *
 * Php version 5.3
 *
 * @category Exception
 * @package  Mesas_Loot_Manager
 * @author   Mesa <daniel.langemann@gmx.de>
 */

namespace JackAssPHP\Helper;

class FileLog
{

    protected $new_entries = array();
    const default_type = "info";
    const type_error = "error";
    const type_info = "info";

    /**
     * Contains the filehandle
     *
     * @var [Object]
     */
    protected $file_handle = null;

    /**
     * Name of Errorlog file
     *
     * @var [String] File name
     */
    protected $file_name = "ErrorLog.txt";

    /**
     * Dont change here, change the path in the constructor
     *
     * @var [String] path to file
     */
    protected $file_path = "";

    public function __construct ()
    {
        $this->file_path = \ROOT . DS . "JackAssPHP" . DS . "Error" . DS;
    }

    public function __destruct ()
    {

        if (count($this->new_entries) > 0) {

            $filename = $this->file_path . $this->file_name;
            $this->file_handle = fopen($filename, "a+");
            $date = time();

            foreach ($this->new_entries as $entry) {
                $message = date("d.m.y H:i:s", $date)
                    . " [" . strtoupper($entry["type"]) . "]  "
                    . trim($entry["message"]) . "\n";
                $message .= "------------------------------------------------------------------\n";
                fwrite($this->file_handle, $message);
            }
            fclose($this->file_handle);
        }
    }

    public function add ( $message, $type = self::default_type )
    {
        $this->new_entries[] = array("message" => $message, "type" => $type);
    }

    /**
     * Singleton implementation.
     *
     * @staticvar Registry $instance
     *
     * @return [Object] Registry
     */
    public static function getInstance ()
    {
        static $instance;
        if (!(isset($instance) and is_object($instance))) {
            $instance = new FileLog();
        }
        return $instance;
    }

}

?>
