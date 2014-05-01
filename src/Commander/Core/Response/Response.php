<?php

namespace Commander\Core\Response;


/**
 * Class ResponseService
 * @package Mesa\Commander\System
 */
class Response implements ResponseInterface
{

    private $content;

    /**
     * @param string $content
     */
    public function __construct($content = "")
    {
        $this->content = $content;
    }

    /**
     * @param string $text
     *
     * @return void
     */
    public function add($text)
    {
        $this->content .= $text;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->content;
    }
}
