<?php

namespace JackAssPHP\Logger;

class Logger
{
    protected $file_path    = null;
    protected $info_logger  = array();
    protected $error_logger = array();

    public function setLogger ( $object )
    {
        $this->loggerObj[] = $class;
    }

    public function setErrorLogger( $object )
    {
        $this->error_logger[] = $object;
    }

    public function setInfoLogger ( $object )
    {
        $this->info_logger[] = $object;
    }

    protected function log ( $type, $msg)
    {
        switch ($type)
        {
        case "info":
            $logger = $this->info_logger;
            break;
        case "error":
            $logger = $this->error_logger;
            break;
        }

        foreach ($logger as $object) {
            $object->log($msg);
        }
    }

    public function info( $msg )
    {
        $this->log("info", $msg);
    }

    public function error( $msg )
    {
        $this->log("error", $msg);
    }


}