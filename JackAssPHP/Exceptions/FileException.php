<?php

/**
 * Loot-Manager
 *
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */
class FileException extends \Exception
{
    public function __construct ( $file, $msg )
    {
        $this->trace = debug_backtrace();
        parent::__construct($msg, $code, $previous);
        $this->message = htmlentities("". date("d.m.y H:i:s")." [File] [".  $this->trace[1]["class"] . $this->trace[1]["type"] .$this->trace[1]["function"] . ":" . $this->trace[0]["line"] ."] " . $msg ."[". $file ."]");
    }
}