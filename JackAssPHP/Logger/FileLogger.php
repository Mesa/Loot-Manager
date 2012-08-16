<?php

namespace JackAssPHP\Logger;

class FileLogger implements \JackAssPHP\Logger\LoggerInterface
{
    protected $file_handle = null;
    protected $file_path   = null;

    public function __construct ( $file_path )
    {
        if (file_exists($file_path) && is_writable($file_path))
        {
            $this->file_path = $file_path . date("y.m.d");
        } else {
            throw new \FileException($file_path, "Log File ist nicht schreibbar");
        }
    }

    public function __destruct ()
    {
        fclose($this->file_handle);
    }

    protected function openFile ()
    {
        $this->file_handle = fopen($this->file_path, "a+");
    }

    public function log ( $msg )
    {
        if ( $this->file_handle == null) {
            $this->openFile();
        }

        fwrite($this->file_handle, "[" . date("H:i:s") . "] " . $msg . "\n");
    }
}