<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace Application\Controller;

use Application\Model\News as NewsModel;

class News extends \JackAssPHP\Core\Controller
{
    /**
     * constructor
     */
    public function __construct ()
    {
        parent::loadSystem();
        $this->rights = \Factory::getRights();
    }
    /**
     * Update News in DB
     *
     * @param type $args Request Args
     */
    public function update ( $args = null )
    {
        if ($this->rights->hasRight("edit_news")) {
            if (isset($args["Id"]) && strlen($args["Id"]) > 0 ) {

                $response = new \JackAssPHP\Core\ResponseJson();
                $id = (int) $args["Id"];
                $headline = filter_var(trim($_POST["headline"]), FILTER_SANITIZE_STRING);
                $content = trim($_POST["content"]);
                $displayFrom = $this->parseTimestamp($_POST["from"]);
                $displayTo = $this->parseTimestamp($_POST["to"]);

                $news_model = new NewsModel();
                $user = \Factory::getUser();

                if ( $displayFrom == 0 ) {
                    $displayFrom = time();
                }
                if ( $displayTo == 0 ) {
                    $displayTo = 2147468400;
                }

                $response->executed = $news_model->editNews(
                    $id,
                    $headline,
                    $content,
                    $user->getUserId(),
                    time(),
                    $displayFrom,
                    $displayTo
                );

                $response->content  = $content;
                $response->to       = date("d.m.Y", $displayTo);
                $response->from     = date("d.m.Y", $displayFrom);
                $response->headline = $headline;
            }
        } else {
//            throw new noRightException("No Right to edit News");
        }
    }
    /**
     * Create a News in the DB
     *
     * @param type $args
     */
    public function create ( $args = null )
    {
        if ($this->rights->hasRight("edit_news")
            && isset($_POST["headline"])
            && isset($_POST["content"])
        ) {
            $response       = new \JackAssPHP\Core\ResponseJson();
            $headline       = filter_var(trim($_POST["headline"]), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $content        = trim($_POST["content"]);
            $displayFrom    = $this->parseTimestamp($_POST["from"]);
            $displayTo      = $this->parseTimestamp($_POST["to"]);
            $newsType       = (int) $args["type"];

            $news_model = new NewsModel();
            $user       = \Factory::getUser();
            if ( $displayFrom == 0 or $displayFrom == "") {
                $displayFrom = time();
            }

            if ( $displayTo == 0 or $displayTo == "") {
                $displayTo = 2147468400;
            }

            $response->executed = $news_model->createNews(
                $headline,
                $content,
                $user->getUserId(),
                time(),
                $displayFrom,
                $displayTo,
                $newsType
            );
            if ( $response->executed ) {
                $response->headline = $headline;
                $response->content  = $content;
                $response->to       = date("d.m.Y", $displayTo);
                $response->from     = date("d.m.Y", $displayFrom);
            }
        }
    }
    /**
     * Drop News entry from DB.
     */
    public function delete ( )
    {
        if ( $this->rights->hasRight("edit_news") &&
            isset($_POST["id"]) ) {
            $id = (int) $_POST["id"];
            $news_model = new NewsModel();
            $response["executed"] = $news_model->deleteNews($id);
            echo json_encode($response);
        }
    }
    /**
     * Parse Timestamp from date String
     *
     * @param [String] $date Date-String to parse
     *
     * @return [Int] Timestamp
     */
    protected function parseTimestamp ( $date )
    {
        if ( $date != null ) {
            preg_match("/(?<day>\d{1,2}).(?<month>\d{1,2}).(?<year>\d{2,4})/", trim($date), $matches);
            return mktime(0, 0, 0, $matches["month"], $matches["day"], (int) $matches["year"]);
        } else {
            return 0;
        }
    }

}

?>
