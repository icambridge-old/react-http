<?php

namespace Icambridge\Http;

interface RequestHeaderParserInterface
{
    public function feed($data);

    public function parseRequest($data);
}
