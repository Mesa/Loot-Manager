<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace Application\Controller;

class Admin extends \JackAssPHP\Core\Controller
{

    /**
     * __constructor
     */
    public function __construct ()
    {
        $this->loadSystem();
        $this->rights = \Factory::getRights();
    }

    /**
     * default page
     *
     * @param type $args URL Args defined in Routes.php
     *
     * @return void
     */
    public function index ( $args = null )
    {
        if ( $this->rights->hasRight("admin_access_dashboard") ) {

            $lang = \Factory::getTranslate();
            $template = \Factory::getView();

            $page = \Factory::getHtmlResponse();
            $page->setTemplate("AdminLayout.html");
            $page->addJavascript($template->load("Admin/js"));
            $page->setTitle($lang->translate("NEWS") . " - " . $this->registry->get("SYSTEM_NAME"));
            $page->addStyleSheet($template->load("News/css"));
            $page->addJsLink( $this->registry->get("WEB_ROOT") . "tiny_mce/jquery.tinymce.js");
            $news_model = new \Application\Model\News();
            $news_list = $news_model->getAdminNews();

            $admin_news = \Factory::getView();
            $admin_news->user_model = \Factory::getUser();

            if ( $this->rights->hasRight("edit_news") ) {
               $page->addJavascript("");
                $hidden_news = $news_model->getHiddenAdminNews();
            }

            if ( count($hidden_news) == 0 && count($news_list) == 0 ) {
                if ( $this->rights->hasRight("edit_news") ) {
                    $all_news[] =
                    array(
                        "Id" => 0,
                        "headline" => "No News",
                        "date" => time(),
                        "content" => "&nbsp;"
                    );
                } else {
                    $admin_news->list = "";
                }
            } else {
                foreach ($news_list as $news ) {
                    $all_news[] = $news;
                }
                foreach ($hidden_news as $news ) {
                    $all_news[] = $news;
                }
            }

            $admin_news->list = $all_news;
            $page->addToContent($admin_news->load("Admin/news"));
        } else {
            throw new \RightException("admin_access_dashboard");
        }
    }
}