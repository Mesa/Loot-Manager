<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace JackAssPHP\Core;

/**
 * Render Template files located under Application\Views
 */
class View
{

    protected $data = null;
    protected $filename = null;
    protected $view_path = null;
    protected $output = null;
    /**
     * @var object $registry Registry class
     */
    protected $registry = null;

    /**
     * Constructor
     */
    public function __construct (
        Registry $registry,
        \JackAssPHP\Helper\Rights $rights,
        \JackAssPHP\Helper\Translate $translate,
        \JackAssPHP\Core\User $user
    ) {
        $this->registry     = $registry;
        $this->view_path    = $this->registry->get("VIEW_PATH");
        $this->theme_path   = $this->registry->get("THEME_PATH");
        $this->theme_path  .= $this->registry->get("SYSTEM_STYLE") .DS;
        $this->web_root     = $this->registry->get("WEB_ROOT");
        $this->rights       = $rights;
        $this->lang         = $translate;
        $this->user         = $user;
    }

    /**
     * Magic set Method
     *
     * @param [String] $name  Var name
     * @param [Mixed]  $value Value
     *
     * @return void
     */
    public function __set ( $name, $value )
    {
        $this->data[$name] = $value;
    }

    /**
     * Magic function to set Template Vars
     *
     * @param [String] $name Name of the variable
     *
     * @return [Mixed]
     */
    public function __get ( $name )
    {
        return $this->data[$name];
    }

    /**
     * Load template and return HTML String.
     *
     * @param [String] $filename name of view file
     *
     * @return [String]
     */
    public function load ( $filename )
    {
        /**
         * Register Array data in local scope to get easy access in template
         */
        if (is_array($this->data)) {
            extract($this->data);
        }

        /**
         * start Server Output caching
         */
        ob_start();
        if (file_exists($this->theme_path . $filename . ".php")) {
            include $this->theme_path . $filename . ".php";
        } elseif (file_exists($this->view_path . $filename . ".php")) {
            include $this->view_path . $filename . ".php";
        } else {
            throw new \ViewException("Template not found (" . $filename . ".php)");
        }

        /**
         * get cached Data
         */
        $template_content = ob_get_contents();

        /**
         * clear cache
         */
        ob_end_clean();
        return $template_content;
    }

    /**
     * Delete all data from previous usage.
     *
     * @return object $this
     */
    public function reset ()
    {
        $this->data = array();
        /**
         * restore default data
         */
        $this->registry = \Factory::getRegistry();
        $this->view_path = $this->registry->get("VIEW_PATH");
        $this->theme_path = $this->registry->get("THEME_PATH");
        $this->theme_path .= $this->registry->get("SYSTEM_STYLE") .DS;
        $this->web_root = $this->registry->get("WEB_ROOT");
        $this->rights = \Factory::getRights();
        $this->lang = \Factory::getTranslate();
        $this->user = \Factory::getUser();
        /**
         * return self, because method chains are cool.
         */
        return $this;
    }

}