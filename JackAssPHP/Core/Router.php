<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace JackAssPHP\Core;

/**
 * Organize all request and ROUTE them to the Class
 */
class Router
{

    /**
     * The default controller, is trigger when user hits the frontpage
     *
     * @var [String] Namespace
     */
    protected $default_controller = null;

    /**
     * Default method to trigger, if no was specified
     *
     * @var [String] Method name
     */
    protected $default_method = null;

    /**
     * The Namespace where usually the class will be found
     *
     * @var [String] Namespace
     */
    protected $default_namespace = null;

    /**
     * the arguments defined in the route RegEx are passed to the Method
     *
     * @var [Array] Arguments passed to the User Method
     */
    protected $routeArgs = array();

    /**
     * All Routes
     *
     * @var [Array]
     */
    protected $routes = array();

    /**
     * Name of the Controller
     *
     * @var [String]
     */
    protected $controller = null;

    /**
     * Name of the Method
     *
     * @var [String]
     */
    protected $method = null;

    /**
     * The Request path from $_SERVER['REQUEST_URI']
     *
     * @var [String] The Request path, that triggered index.php
     */
    protected $request = null;

    /**
     * If the defined Controller couldn't be triggered, the error controller
     * will handle the error with this method.
     *
     * @var [String] If controller is not found, trigger this method
     */
    protected $file_error = "FileNotFound";
    protected $route_error = "RouteNotFound";
    protected $route_no_request_method = "noRequestMethod";

    /**
     * Name of the Error class
     * @var [String]
     */
    protected $error_controller = "\\JackAssPHP\\Core\\Error";
    protected $registry = null;

    /**
     * Constructor
     *
     * @param $registry  Registry class
     * @param $blacklist Blacklist class
     * @param $routes    All route information
     *
     * @return void
     */
    public function __construct ( $registry, $routes )
    {
        /**
         * Default Routes
         */
        $this->registry = $registry;

        $this->default_namespace = $this->registry->get("DEFAULT_NAMESPACE");
        $this->default_controller = $this->registry->get("DEFAULT_CONTROLLER");
        $this->default_method = $this->registry->get("DEFAULT_METHOD");
        $this->error_controller = $this->registry->get("ERROR_CONTROLLER");
        $this->request = $this->registry->get("REQUEST_PATH");
        $this->args = $this->registry->get("REQUEST_ARGS");
        $this->request_method = $this->registry->get("REQUEST_METHOD");
        $this->routes = $routes;
        $this->findRoute();
        $this->triggerController();
    }

    /**
     * Trigger the UserControler Method.
     *
     * @todo implement a switch to load cached template files
     *
     * @return void
     */
    protected function triggerController ()
    {
        /**
         * change the namespace if the default controller is triggered
         */
        if (substr($this->controller, 0, 1) == "\\") {
            $user_controller = $this->controller;
        } else {
            $user_controller = $this->default_namespace . $this->controller;
        }

        if (DS == "/") {
            /**
             * Replace the namespace backslashes to normal slashes for
             * path use. /var/www/Application\Controller\ will fail
             * on linux.
             */
            $replaced_slashed = str_replace("\\", "/", $user_controller);
        } else {
            $replaced_slashed = $user_controller;
        }

        $user_method = $this->method;
        if (file_exists(ROOT . $replaced_slashed . ".php")) {

            $controller = new $user_controller();
            if (method_exists($controller, $user_method)) {
                $controller->$user_method($this->routeArgs);
            }

        } else {

            $this->controller = $this->error_controller;
            $this->method = $this->file_error;
            $this->triggerController();
        }
    }

    /**
     * Set the route information
     *
     * @return void
     */
    protected function findRoute ()
    {

        if (strlen($this->request) !== 0) {

            foreach ($this->routes as $path => $data) {
                /**
                 * @todo: escape all special chars, not only the slash
                 */
                $escaped_regex = str_replace("/", "\/", $path);
                $match = array();
                preg_match("/^$escaped_regex/", $this->request, $match);
                /**
                 * If a route matches, get the routing information,
                 * set Controller and Method.
                 */
                if (count($match) >= 1) {

                    if (isset($data[$this->request_method])) {
                        $route_data = $data[$this->request_method];
                        array_shift($match);
                        $this->routeArgs = $match;
                    } elseif (isset($data['*'])) {
                        $route_data = $data['*'];
                        array_shift($match);
                        $this->routeArgs = $match;
                    } else {
                        /**
                         * If route matches, but there is no matching
                         * request method, load the Error Controller
                         */
                        $this->controller = $this->error_controller;
                        $this->method = $this->route_no_request_method;
                        break;
                    }
                    $this->extractRouteString($route_data);
                    /**
                     * Stop the loop to fire no second event.
                     * One match is still enough.
                     */
                    break;
                }
            }
        } else {
            /**
             * load the default Controller, because the Root site was visited.
             */
            $this->extractRouteString(
                    $this->default_controller
                    . "/"
                    . $this->default_method
            );
        }
        /**
         * If no Route matches and NOT the default page was triggered.
         */
        if ($this->controller == null) {
            $this->controller = $this->error_controller;
            $this->method = $this->route_error;
        }
    }

    /**
     * Set Controller and Method from string.
     *
     * @param [String] $string parse Controller and method from string
     */
    protected function extractRouteString ( $string )
    {
        $match = explode("/", $string);
        $match["controller"] = $match[0];

        $this->controller = $match[0];

        if (isset($match[1])) {
            $this->method = $match[1];
        } else {
            $this->method = $this->default_method;
        }
    }

}