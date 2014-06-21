<?php

namespace Icambridge\Http\Request;

class BodiedRequest extends Request
{
    private $body;

    public function __construct($method, $path, $query = array(), $httpVersion = '1.1', $headers = array(), $body = "")
    {
        $this->body = $body;
        parent::__construct($method, $path, $query, $httpVersion, $headers);
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }
}
