<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace JackAssPHP\Core;

/**
 * Controller to handle file delivering
 */
class Files
{

    /**
     * Get content of a image file and return it to the client
     *
     * @param [Array] $args URL arguments
     *
     * @return void
     */
    public function image ( $args )
    {

        $registry = \Factory::getRegistry();

        $web_root = $registry->get("WEB_ROOT");

        $image_path = $registry->get("APPLICATION_PATH") . "Images" . DS;

        $path = str_replace(
            "/" . basename($web_root) . "/", "", $_SERVER["REQUEST_URI"]
        );

        if ( dirname($path) == "layout" ) {
            $image_path = $registry->get("THEME_PATH")
                . $registry->get("SYSTEM_STYLE")
                . "/images/";

            $path = basename($path);
        }

        $file_type = pathinfo($path);
        if (file_exists($image_path . $path)) {

            switch (strtolower($file_type["extension"])) {
            case "gif":
                $ctype = "image/gif";
                break;
            case "png":
                $ctype = "image/png";
                break;
            case "jpeg":
            case "jpg":
                $ctype = "image/jpg";
                break;
            default: $ctype = "application/force-download";
            }

            $this->sendCachingHeader();
            header('Content-type: ' . $ctype);
            echo file_get_contents($image_path . $path);
        } else {
            /**
             * File was not found
             */
            header("HTTP/1.0 404 Not Found");
        }
    }

    /**
     * Send Cache Controll header, to allow caching for images, css etc...
     */
    protected function sendCachingHeader ()
    {
        /**
         * @todo change Headercaching to dynamic created date, one week in future?
         */
        if (\PRODUCTION_USE) {
            header("Cache-Control: public");
            header("Expires: Sat, 26 Jul 2029 05:00:00 GMT");
        } else {
            /**
             * suppress caching for development purpose
             */
            header("Cache-Control: no-cache, must-revalidate");
            header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
        }
    }

    /**
     * Get content of a javascript file and return it to the client
     *
     * @param [Array] $args URL arguments
     *
     * @return void
     */
    public function javascript ( $args )
    {
        $registry = \Factory::getRegistry();

        $js_folder = $registry->get("JAVASCRIPT_PATH");
        $filename = $args["filename"];
        $dirname = str_replace("..", " ", $args["dirname"]);

        if (file_exists($js_folder . $dirname . $filename . ".js")) {
            $content = file_get_contents($js_folder . $dirname . $filename . ".js");

            $this->sendCachingHeader();
            header("content-type: application/x-javascript");
            echo $content;
        } else {
            header("HTTP/1.0 404 Not Found");
        }
    }

    /**
     * Get content of a css file and return it to the client
     *
     * @param [Array] $args URL arguments
     *
     * @return void
     */
    public function css ( $args )
    {
        $registry = \Factory::getRegistry();

        $css_folder
            = $registry->get("APPLICATION_PATH") .
            "CSS" .
            \DIRECTORY_SEPARATOR;

        if (isset($args["filename"])) {

            $filename = $args["filename"];
            if (file_exists($css_folder . $filename . ".css")) {
                $content = file_get_contents($css_folder . $filename . ".css");
                $this->sendCachingHeader();
                header("content-type: text/css");
                echo $content;
            } else {
                header("HTTP/1.0 404 Not Found");
            }
        } else {
            header("HTTP/1.0 404 Not Found");
        }
    }

}