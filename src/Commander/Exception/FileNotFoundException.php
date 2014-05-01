<?php

namespace Commander\Exception;

/**
 * Class FileNotFoundException
 *
 * @package Commander\Exception
 */
class FileNotFoundException extends \Exception
{
    public function __toString()
    {
        return "File not found [" . $this->message . "]";
    }
}
