<?php

namespace Commander\Core\Response;

/**
 * Class to print your response instant.
 *
 * @package Mesa\Commander\System\Response
 */
class DirectResponse implements ResponseInterface
{

    /**
     * @param string $content
     */
    public function __construct($content = "")
    {
        echo $content;
    }

    /**
     * @param $text
     *
     * @return mixed
     */
    public function add($text)
    {
        echo $text;
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return "";
    }
}
