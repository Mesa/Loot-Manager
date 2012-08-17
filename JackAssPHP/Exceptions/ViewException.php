<?php

/**
 * Loot-Manager
 *
 * @author   Mesa <daniel.langemann@gmx.de>
 */

class ViewException extends \Exception
{

    public function __construct ( $msg )
    {
        parent::__construct();
        $this->message = $msg;
    }

    public function errorMessage ()
    {
        if ( \PRODUCTION_USE !== true ) {

            $trace = $this->getTrace();

            echo "<b>[VIEW]</b> $this->message
                        Class: " . $trace[1]["class"] . " -> " . $trace[1]["function"] . ":" . $trace[0]["line"] . "<br>\n
                        <hr>\n";
        }

        $log = \Factory::getLogger();
        $log->Error("[View] $this->message  " . $trace[1]["class"] . "->" . $trace[1]["function"] .":".$trace[0]["line"]);
    }

}