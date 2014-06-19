<?php

namespace Icambridge\Tests\Http;

use Evenement\EventEmitter;
use Icambridge\Http\Request\Request;
use Icambridge\Http\Request\RequestHeaderParserInterface;

class ParserStub extends EventEmitter implements RequestHeaderParserInterface
{
    public function feed($data)
    {
        $request = new Request("GET", "/");
        $this->emit('headers', array($request, ""));
    }

    public function parseRequest($data)
    {
    }
}
