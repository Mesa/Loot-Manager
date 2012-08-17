<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace Application\Controller;

class FrontPage extends \JackAssPHP\Core\Controller
{

    /**
     * Constructor
     */
    public function __construct ()
    {
        $this->loadSystem();
    }

    /**
     * Displays the main page
     *
     * @return void
     */
    public function index ( $args = null )
    {
        $this->rights = \Factory::getRights();
        $news = \Factory::getView();
        $template = \Factory::getView();

        $page = \Factory::getHtmlResponse();
        $page->setTemplate("Layout.html");
        $page->addStylesheet($template->load("News/css"));
        $news_model = new \Application\Model\News();

        $news->list = $news_model->getFrontpageNews();
        $news->user_model = new \Application\Model\User();
        if ( $this->rights->hasRight("edit_news")) {
            $page->addJsLink( $this->registry->get("WEB_ROOT") . "tiny_mce/jquery.tinymce.js");
            $page->addToContent($template->load("FrontPage/submenu"));
        }
        if ( count($news->list) == 0 && $this->rights->hasRight("edit_news") ) {
                $news->list = array( array("headline"=>"No News in DB", "author"=>2 ) );
        }
        if ( $this->rights->hasRight("edit_news")) {
            $news->hidden_list = $news_model->getHiddenFrontpageNews();
        }
        $page->addToContent($news->load("FrontPage/news"));
    }

    /**
     * Dislays the Impressum
     *
     * @return void
     */
    public function impressum ()
    {
        $html = \Factory::getHtmlResponse();
        $html->setTemplate("Layout.html");
        $template = \Factory::getView();

        $html->addToContent($template->load("FrontPage/impressum"));
    }
}
