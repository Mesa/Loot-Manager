<?php

/**
 * Loot-Manager
 *
 * @author Mesa <daniel.langemann@gmx.de>
 */

namespace JackAssPHP\Core;

class ResponseHtml
{
    protected $template_path = null;
    private $_data = array();
    protected $response_code = 200;

    public function __construct( )
    {
        $this->_data["content"] = "";
        $this->view = \Factory::getView();
    }

    protected function load( $file )
    {
        return $this->view->load($file);
    }

    public function __destruct ()
    {

        if ( $this->response_code != 200 ) {
            header("HTTP/1.0 ". $this->response_code ."");
        }

        if ( $this->template_path == null) {
            throw new \JackAssPHP\Exceptions\HtmlException("No Template" . $this->template_path);
        }
            extract($this->_data);
            include $this->template_path;
    }

    public function addJsLink ( $path )
    {
        $this->_data["js_link"][] = $path;
    }

    public function addJavascript ( $content )
    {
        $this->_data["javascript"] .= $content;
    }

    public function addStyleLink ( $path )
    {
        $this->_data["style_link"][] = $path;
    }

    public function addStyleSheet ( $content )
    {
        $this->_data["css"] .= $content;
    }

    public function addToContent ( $html )
    {
        $this->_data["content"] .= $html;
    }

    public function addToHeader ( $html )
    {
        $this->_data["header"] = $html;
    }

    public function addToFooter ( $html )
    {
        $this->_data["footer"] = $html;
    }

    public function addMetaData ( $meta_data )
    {
        $this->_data["meta_data"][] = $meta_data;
    }

    public function setResponseCode ( $code )
    {
        $this->response_code = (int) $code;
    }

    public function setTitle ( $title )
    {
        $this->_data["title"] = $title;
    }

    public function setDocType ()
    {

    }

    public function setThemePath ( $path )
    {
        $this->theme_path = $path;
    }
    public function setViewPath ( $path )
    {
        $this->view_path = $path;
    }

    public function setTemplate ( $filename )
    {
        if (file_exists( $this->theme_path . $filename . ".php") ) {
            $this->template_path = $this->theme_path . $filename . ".php";
        } elseif (file_exists ($this->view_path . $filename . ".php" )) {
            $this->template_path = $this->view_path . $filename . ".php";
        } else {
            throw new \JackAssPHP\Exceptions\HtmlException($this->theme_path.$filename);
        }
    }

    public function setWebRoot ( $web_root )
    {
        $this->_data["web_root"] = $web_root;
    }
}