<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace Application\Model;

class News extends \JackAssPHP\Core\DataModel
{

    protected $table_name = "news";

    /**
     * constructor
     */
    public function __construct ()
    {
        parent::__construct();
    }

    public function createNews ( $headline, $content, $author, $date, $displayFrom = 0, $displayTo = 0, $type )
    {
        if ( $displayFrom == null ) {
            $displayFrom = 0;
        }

        if ( $displayTo == null ) {
            $displayTo = 2147468400;
        }

        $request = $this->getStatement(
            "INSERT INTO `$this->table_name`
            (`headline`, `content`, `author`, `date`, `show_from`,`show_until`,`type`)
            VALUES
            (:headline, :content, :author, :date, :from, :until, :type);"
        );
        $request->bindValue(":headline", $headline, \PDO::PARAM_STR);
        $request->bindValue(":content", $content, \PDO::PARAM_STR);
        $request->bindValue(":author", $author, \PDO::PARAM_STR);
        $request->bindValue(":date", $date, \PDO::PARAM_STR);
        $request->bindValue(":from", $displayFrom, \PDO::PARAM_INT);
        $request->bindValue(":until", $displayTo, \PDO::PARAM_INT);
        $request->bindValue(":type", $type, \PDO::PARAM_INT);
        return $request->execute();
    }

    public function deleteNews ( $id )
    {
        if ( $id > 0 ) {
            $request = $this->getStatement("DELETE FROM `$this->table_name` WHERE `id`= :id;");
            $request->bindValue(":id", $id, \PDO::PARAM_INT);
            return $request->execute();
        } else {
            return false;
        }
    }

    public function editNews (
        $id,
        $headline,
        $content,
        $author,
        $date,
        $displayFrom = null,
        $displayTo = null
    ) {
        if ( $displayFrom == null ) {
            $displayFrom = 0;
        }

        if ( $displayTo == null ) {
            $displayTo = 2147468400;
        }

        $request = $this->getStatement(
            "UPDATE `$this->table_name` SET
                `content` = :content,
                `headline` = :headline,
                `date` = :date,
                `show_from` = :from,
                `show_until` = :until,
                `author` = :author
                WHERE `id` = :id"
        );

        $request->bindValue(":from", $displayFrom, \PDO::PARAM_INT);
        $request->bindValue(":until", $displayTo, \PDO::PARAM_INT);
        $request->bindValue(":id", $id, \PDO::PARAM_INT);
        $request->bindValue(":headline", $headline, \PDO::PARAM_STR);
        $request->bindValue(":content", $content, \PDO::PARAM_STR);
        $request->bindValue(":author", $author, \PDO::PARAM_STR);
        $request->bindValue(":date", $date, \PDO::PARAM_STR);

        return $request->execute();
    }

    public function getHiddenFrontpageNews ()
    {
        $request = $this->getStatement("SELECT * FROM `$this->table_name` WHERE `type` = '2' AND (`show_from` > :timestamp OR `show_until` < :timestamp) ORDER BY `date` DESC");
        $request->bindValue(":timestamp", time());
        $request->execute();

        return $request->fetchAll();
    }

    public function getHiddenAdminNews ()
    {
        $request = $this->getStatement("SELECT * FROM `$this->table_name` WHERE (`type` = '1') AND (`show_from` > :timestamp OR `show_until` < :timestamp) ORDER BY `date` DESC");
        $request->bindValue(":timestamp", time());
        $request->execute();

        return $request->fetchAll();
    }

    public function getAdminNews ()
    {
        $request = $this->getStatement("SELECT * FROM `$this->table_name` WHERE (`type` = '1') AND (`show_from` <= :timestamp AND `show_until` > :timestamp) ORDER BY `date` DESC");
        $request->bindValue(":timestamp", time());
        $request->execute();

        return $request->fetchAll();
    }

    public function getFrontpageNews ()
    {
        $request = $this->getStatement("SELECT * FROM `$this->table_name` WHERE (`type` = '2') AND (`show_from` <= :timestamp AND `show_until` > :timestamp) ORDER BY `date` DESC");
        $request->bindValue(":timestamp", time());
        $request->execute();
        return $request->fetchAll();
    }
}

?>
