<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace JackAssPHP\Core;

use JackAssPHP\Core\View as Template;

/**
 * Controlls all error messages and displays them to the user.
 */
class Error extends Controller
{

    protected $error_log = null;

    public function __construct ()
    {
        $this->error_log = new \JackAssPHP\Helper\FileLog();
    }

    public function FilenotFound ()
    {
        $layout = new Template();

        echo $layout->load("Error");
//        $this->error_log->add("File not found" . print_r(debug_backtrace(), true), "File");
    }

    public function RouteNotFound ()
    {
        $layout = new Template();
        echo $layout->load("Error");
//        $this->error_log->add("no route was found", "Routes");
    }

    public function noRequestMethod ()
    {
        $layout = new Template();
        echo $layout->load("Error");
//        $this->error_log->add("no Requestmethod was found", "Routes");
    }

    public function noDBConnection ( $message )
    {
        $layout = new Template();
        echo $layout->load("DBError");

//        $this->error_log->add($message, "DB ERROR");
        die();
    }

}