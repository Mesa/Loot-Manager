<?php

/**
 * Loot-Manager
 *
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace JackAssPHP\Helper;

class DropDownMenu
{

    protected $data = array();
    protected $menuRoot = true;

    public function __construct ()
    {
        /**
         * @todo use Dependency Injection!!
         */
        $this->registry = \Factory::getRegistry();
    }

    public function loadXml ( $path )
    {
        if (file_exists($path)) {
            $this->lang = \Factory::getTranslate();
            $this->rights = \Factory::getRights();

            $this->web_root = $this->registry->get("WEB_ROOT");
            $this->xml = new \DOMDocument();
            $this->xml->preserveWhiteSpace = false;
            $this->xml->load($path);
            $xPath = new \DOMXPath($this->xml);
            $data = $xPath->query("/root/item");
            $this->buildFromXml($data, $this->data);
        } else {
            throw new \Exception("Menu File doesn't exist " . $path);
        }

    }

    public function loadDb ( )
    {
        $this->loadXml($this->registry->get("CONFIG_PATH")."FrontPageMenu.xml");
        $menu_dao = new \Application\Model\MenueLinks();

        $data = $menu_dao->loadDropDownMenu();
        if (count($data) > 0) {
            $this->addArray(
                array( array("icon" => "link.png", "name"=>"Links","children"=>$data) )
            );
        }
    }

    public function addArray ( $array )
    {
        $new = array_merge($this->data, $array);
        $this->data = $new;
    }

    protected function buildFromXml ( $item, &$menuItem )
    {

        for ($i = 0; $i < $item->length; $i++) {
            $childs = $this->hasChildItem($item->item($i));
            $rights = $this->getChildValue($item->item($i), "rights/right");

            $allowed = false;
            if ($rights !== false) {
                if ( is_array($rights) ) {
                    foreach ( $rights as $check) {
                        if ( $this->rights->hasRight($check)) {
                            $allowed = true;
                            /**
                            * stop the loop, because one right is enought
                            */
                            break;
                        }
                    }
                } else {
                    $allowed = $this->rights->hasRight($rights);
                }
            } else {
                $allowed = true;
            }

            if($allowed) {
                $menuItem[$i]["name"] = $this->lang->translate($this->getChildValue($item->item($i), "name"));
                $menuItem[$i]["link"] = $this->getChildValue($item->item($i), "link");
                $menuItem[$i]["target"] = $this->getChildValue($item->item($i), "target");
                $menuItem[$i]["icon"] = $this->getChildValue($item->item($i), "icon");
            }

            if ($childs->length > 0) {
                $this->buildFromXml($childs, $menuItem[$i]["children"]);
            }
        }
    }

    protected function hasChildItem ( $item )
    {
        $nodePath = $item->getNodePath();
        $xPath = new \DOMXPath($this->xml);
        $data = $xPath->query($nodePath . "/item");
        return $data;
    }

    protected function getChildValue ( $item, $name )
    {
        $nodePath = $item->getNodePath();
        $xPath = new \DOMXPath($this->xml);
        $data = $xPath->query($nodePath . "/" . $name);

        if ($data->length > 1) {
            $array = array();
            for($i=0; $i < $data->length; $i++) {
                $array[] = $data->item($i)->nodeValue;
            }
            return $array;
        } elseif ($data->length > 0) {
            return $data->item(0)->nodeValue;
        } else {
            return false;
        }
    }

    public function getHtml ()
    {
        return $this->buildList($this->data);
    }

    protected function buildList ( $list )
    {
        if ($this->menuRoot == true) {
            $data = "<ul class=\"topnav\" id=\"menu\">";
            $this->menuRoot = false;
        } else {
            $data = "<ul class=\"subnav\">";
        }

        foreach ($list as $item) {
            if (isset($item["target"]) and $item["target"] != false) {
                $target = " target=\"" . $item["target"] . "\" ";
            } else {
                $target = "";
            }

            if (isset($item["icon"]) and $item["icon"] != false) {
                $icon = "<div class=\"icon\"><img src=\"" . $this->web_root . $item["icon"] . "\" alt=\"\"/></div>";
            } else {
                $icon = "";
            }

            if (isset($item["link"]) and  $item["link"] !== false) {

                if(substr($item["link"], 0, 7) == "http://") {
                    $data .= "<li><a href=\"" . $item["link"] . "\"$target>" . $icon . $item["name"] . "</a>";
                } else {
                    $data .= "<li><a href=\"" . $this->web_root . $item["link"] . "\"$target>" . $icon . $item["name"] . "</a>";
                }
            } else {
                $data .= "<li><a>" . $icon . $item["name"] . "</a>";
            }

            if (isset($item["children"])) {
                $data .= $this->buildList($item["children"]);
            }
            $data .= "</li>";
        }

        $data .= "</ul>";
        return $data;
    }

}