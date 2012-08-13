<?php

/**
 * Mesas Loot Manager
 * Copyright (C) 2011  Mesa <Daniel Langemann>
 *
 * Php version 5.3
 *
 * @category Library_Wrapper
 * @package  Mesas_Loot_Manager
 * @author   Mesa <daniel.langemann@gmx.de>
 */

namespace JackAssPHP\Library;

/**
 * Wrapper class to server TinyMCE files through the routed system, with no direkt
 * filesystem acces.
 */
class TinyMce
{

    protected $folder_path = "JackAssPHP/Library/tiny_mce/";

    public function __construct ()
    {

    }

    public function index ( $args = null )
    {
        $filename = $args["path"];
        $filetype = $args["filetype"];

        if (file_exists($this->folder_path . $filename . "." . $filetype)) {

            switch ($filetype) {

            case "gif":
                header("content-type: image/gif");
                break;
            case "js":
                header("content-type: application/x-javascript");
                break;
            case "html":
                header("content-type: text/html");
                break;
            case "htm":
                header("content-type: text/html");
                break;
            case "css":
                header("content-type: text/css");
                break;
            }

            echo file_get_contents($this->folder_path . $filename . "." . $filetype);
        }
    }

}