<?php

namespace Icambridge\Tests\Http;

use Evenement\EventEmitter;
use Icambridge\Http\Request\Request;
use Icambridge\Http\Request\ParserInterface;

class ParserStub extends EventEmitter implements ParserInterface
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
