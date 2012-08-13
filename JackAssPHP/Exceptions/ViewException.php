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

namespace JackAssPHP\Exceptions;

class ViewException extends \Exception
{

    public function errorMessage ()
    {
        if ( \PRODUCTION_USE !== true ) {

            $trace = $this->getTrace();

            echo "<b>[VIEW]</b> Template <u><span style='color: blue'>$this->message</span></u> is missing. <br>
                        Line: " . $trace[0]["line"] . "<br>\n
                        File: " . $trace[0]["file"] . "<br>\n
                        Class: " . $trace[1]["class"] . " -> " . $trace[1]["function"] . "<br>\n
                        <hr>\n";
            return true;
        } else {
            /**
             * @todo add file log entry
             */
        }
    }

}