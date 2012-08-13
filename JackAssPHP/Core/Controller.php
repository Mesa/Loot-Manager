<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace JackAssPHP\Core;

/**
 * Main controller, is extended by User Controller
 */
class Controller
{

    protected $template = null;
    protected $registry = null;

    /**
     * default called method
     *
     * @return void
     */
    public function index ( $args = null )
    {
        /**
         * @todo implement errorhandling
         */
        echo "No index Method defined";
    }

    /**
     * load all System classes to use the Controller as a superclass
     *
     * @return void
     */
    protected function loadSystem ()
    {
        if (! isset($_SESSION) ) {
            session_start();
        }
        $this->registry = \Factory::getRegistry();
    }

}