<?php

namespace Icambridge\Http\Request;

interface RequestHeaderParserInterface
{
    public function feed($data);

    public function parseRequest($data);
}
