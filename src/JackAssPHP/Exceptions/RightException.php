<?php

/**
 * Description of NoRightsException
 *
 * @author mesa
 */
class RightException extends \Exception
{

    public function __construct ( $msg )
    {
        parent::__construct();
        $template = \Factory::getView();
        echo $template->load("ExceptionMessage/accessDenied");
        /**
         * @todo add Logging to file or DB
         */
    }

}
