<?php

namespace JackAssPHP\Error;

class ExceptionErrorMessage 
{
    protected $exception = null;
    protected $error_name = "ERROR";
    
    public function __construct ( $exc_obj, $error_name = null )
    {
        if ( $error_name != null ) {
            $this->error_name = $error_name;
        }
        
        $this->exception = $exc_obj;
        
        $this->errorMessage();
    }
    
    public function errorMessage ( )
    {
            $trace = $this->exception->getTrace();
            $message = $this->exception->getMessage();
            echo "<b>[$this->error_name]</b> <span style='color: blue'>$message</span><br>
                    Line: ". $trace[0]["line"] . "<br>\n
                    File: ". $trace[0]["file"] . "<br>\n
                    Class: ". $trace[1]["class"] . " -> " . $trace[1]["function"] . "<br>\n
                    <hr>\n";
    }    
}