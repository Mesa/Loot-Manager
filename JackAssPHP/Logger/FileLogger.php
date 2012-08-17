<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace JackAssPHP\Logger;

class FileLogger implements \JackAssPHP\Logger\LoggerInterface
{
    protected $file_handle = null;
    protected $file_path   = null;

    /**
     * Constructor
     *
     * @param [String] $file_path path to logfile
     *
     * @throws \FileException
     */
    public function __construct ( $file_path )
    {
        if (file_exists($file_path) && is_writable($file_path)) {
            $this->file_path = $file_path . date("y.m.d");
        } else {
            throw new \FileException($file_path, "Log File ist nicht schreibbar");
        }
    }

    public function __destruct ()
    {
        fclose($this->file_handle);
    }

    /**
     * Open logfile an save filehandle
     *
     * @return void
     */
    protected function openFile ()
    {
        $this->file_handle = fopen($this->file_path, "a+");
    }

    /**
     * Write message to log file
     *
     * @param [String] $msg date(H:i:s) . $msg
     *
     * @return void
     */
    public function log ( $msg )
    {
        if ( $this->file_handle == null) {
            $this->openFile();
        }

        fwrite($this->file_handle, "[" . date("H:i:s") . "] " . $msg . "\n");
    }
}