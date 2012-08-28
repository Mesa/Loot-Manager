<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace JackAssPHP\Core;

/**
 * Default controller is called when no other route matches.
 * 
 * @todo Rewrite Default controller, the used classes doesnt exist anymore
 */
class DefaultController extends Controller
{

    /**
     * Constructor
     */
    public function __construct ()
    {
        /**
         * call the default method to load mostly needed classes, or do it on your
         * own and delete the following line.
         */
        parent::loadSystem();
    }

    /**
     * default called Method
     *
     * @return void
     */
    public function index ( $args = null )
    {
//        $body = new Template();
//        $body->title = $this->registry->get("PROJECT_NAME") .
//            " " . $this->registry->get("VERSION_NR");
//
//        $layout = new Template();
//        $layout->title = $this->registry->get("PROJECT_NAME");
//        $layout->debug = "";
//        $layout->body = $body->load("index_body");
//
//        $helper = HtmlPage::getInstance();
//        $meta = array("author" => "Mesa");
//        $helper->addMeta($meta);
//        $meta = array("charset" => "utf-8");
//        $helper->addMeta($meta);
//
//        $layout->meta = $helper->getMetaData();
//        $layout_content = $layout->load("main");
//
//        echo $layout_content;
    }

}

