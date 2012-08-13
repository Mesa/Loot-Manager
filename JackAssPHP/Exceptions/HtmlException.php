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

class HtmlException extends \Exception
{

    public function errorMessage ()
    {
        if ( \PRODUCTION_USE !== true ) {

            $trace = $this->getTrace();

            echo "[<b style=\"color:steelblue\">HtmlResponse</b>]<br> Template [<span style='color: steelblue'>$this->message</span>] is missing.<br>
                        File: <b style=\"color:steelblue\">" . $trace[0]["file"] . "</b> : <b style=\"color:steelblue\">" . $trace[0]["line"] . "</b> <br>\n
                        Class: " . $trace[1]["class"] . " -> " . $trace[1]["function"] . "<br>\n
                        <hr>\n";
        } else {
            /**
             * @todo add file log entry
             */
        }
    }

}