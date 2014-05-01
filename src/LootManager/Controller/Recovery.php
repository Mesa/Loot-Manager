<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace Application\Controller;

class Recovery extends \JackAssPHP\Core\Controller
{

    public function __construct()
    {
        parent::loadSystem();
    }

    public function index ()
    {
        $template = \Factory::getView();

        $page = \Factory::getHtmlResponse();
        $page->setWebRoot($this->registry->get("WEB_ROOT"));
        $page->setThemePath(
            $this->registry->get("THEME_PATH").$this->registry->get("SYSTEM_STYLE").DS
        );
        $page->setTemplate("Layout.html");
        $page->addToContent($template->load("Recovery/content"));
    }

}