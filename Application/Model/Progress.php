<?php

namespace Application\Model;

class Progress extends \JackAssPHP\Core\DataModel
{
    protected $table_name = "Encounter";
    protected $game_data = array();
    protected $file_name = "progress.xml";


    public function getData( $game )
    {
        $registry = \Factory::getRegistry();

        $file_path = $registry->get("CONFIG_PATH") . $this->file_name;
        if (file_exists($file_path)) {
            $dom = new \DOMDocument();
            $dom->load($file_path);

            $xpath = new \DOMXPath($dom);
            $dungeon = $xpath->query($game . "/dungeon");
            $length  = $dungeon->length;

            $data = array();

            for ( $i = 0; $i < $length ; $i++) {
                $tmp = array();
                $tmp["name"] = $dungeon->item($i)->getAttribute("name");
                $clear_counter = 0;
                $boss_counter  = 0;

                foreach ($dungeon->item($i)->childNodes as $child) {
                    $boss = array();
                    if ( $child->nodeType == XML_ELEMENT_NODE) {
                        $boss["name"] = $child->getAttribute("name");
                        $boss["status"] = $this->getStatus($boss["name"]);

                        $boss_counter++;
                        
                        if ($boss["status"] == "clear") {
                            $clear_counter++;
                        }
                        $tmp["boss"][] = $boss;
                    }
                }
                $tmp["clear"] = $clear_counter;
                $tmp["boss_count"] = $boss_counter;
                $data[] = $tmp;
            }
            return $data;
        } else {
            throw new \FileException($file_path, "Progress data file not found");
        }
    }
    /**
     * count child nodes and ignore text nodes
     *
     * @param [DomElement] $node DomElement Knoten
     *
     * @return [Int] Child count
     */
    protected function countChildNodes ( $node )
    {
        if ($node->hasChildNodes() === true) {

            $z = 0;
            foreach ($node->childNodes as $child) {
                if ($child->nodeType !== XML_TEXT_NODE) {
                    $z++;
                }
            }
            return $z;
        } else {
            return 0;
        }
    }
    
    public function toggleStatus ( $name ) 
    {
        $status = $this->getStatus($name);
        if ($status === "no") {
            $statement = $this->getStatement("INSERT INTO `$this->table_name` (`Name`, `Status`) VALUES (:name, :status);");
            $status = false;
        } else {
            $statement = $this->getStatement("UPDATE `$this->table_name` SET `Status` = :status WHERE `Name` = :name LIMIT 1;");
        }
        
        $status = 1 ^ $status;
        $statement->bindValue(":name", $name);
        $statement->bindValue(":status", $status);
        return $statement->execute();
    }
    
    public function getStatus ( $name )
    {
        $statement = $this->getStatement("SELECT `Status` FROM `$this->table_name` WHERE `Name` = :name LIMIT 1");
        $statement->bindValue(":name", $name);
        $status = $statement->execute();
        
        if ($status === true) {
            $result = $statement->fetch();
            if ( $result === false) {
                return "no";
            } else {
                return (bool) $result["Status"];
            }
        }
    }
}