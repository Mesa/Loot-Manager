<?php

namespace Commander\Core\Response;

/**
 * Class HttpResponse
 *
 * @package Mesa\Commander\System\Response
 */
class HttpResponse implements ResponseInterface
{
    const OK_200                 = "200 OK";
    const BAD_REQUEST_400        = "400 Bad Request";
    const UNAUTHORIZED_401       = "401 Unauthorized";
    const FORBIDDEN_403          = "403 Forbidden";
    const NOT_FOUND_404          = "404 Not Found";
    const METHOD_NOT_ALLOWED_405 = "405 Method not Allowed";
    const ERROR_500              = "500 Server Error";
    const TMP_REDIRECT_302       = "302 Found";
    const PERM_REDIRECT_301      = "301 Moved Permanently";
    const NOT_MODIFIED_304       = "304 Not Modified";
    private $content;
    private $responseCode;
    /**
     * @Inject("Twig")
     */
    public $twig;

    /**
     *
     */
    public function __construct()
    {
        $this->responseCode = self::OK_200;
    }

    public function setData(array $data, $template)
    {
        $this->content = $this->twig->render($template, $data);
    }

    /**
     * @return string
     */
    public function getResponseCode()
    {
        return $this->responseCode;
    }

    /**
     * @param $responseCode
     *
     * @return $this
     */
    public function setResponseCode($responseCode)
    {
        $this->responseCode = $responseCode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        if (!headers_sent()) {
            header('HTTP/1.0 ' . $this->responseCode);
        }

        return $this->content;
    }
}
