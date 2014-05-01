<?php

namespace Commander\Error;


/**
 * Class Error404
 *
 * @package Commander\Error
 */
class Error404
{
    /**
     * @return string
     */
    public function __toString()
    {
        return "\n \tNo Route found\n\n";
    }
}
