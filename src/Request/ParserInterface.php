<?php

namespace Icambridge\Http\Request;

interface ParserInterface
{
    public function feed($data);

    public function parseRequest($data);
}
