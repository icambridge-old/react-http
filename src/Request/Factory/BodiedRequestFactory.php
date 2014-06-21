<?php

namespace Icambridge\Http\Request\Factory;

use Icambridge\Http\Request\Request;

class BodiedRequestFactory implements RequestFactoryInterface
{
    public function get(array $data)
    {
        $parsedQuery = array();
        if (isset($data['request_url']['query'])) {
            parse_str($data['request_url']['query'], $parsedQuery);
        }

        return new Request(
            $data['method'],
            $data['request_url']['path'],
            $parsedQuery,
            $data['version'],
            $data['headers'],
            $data['body']
        );
    }
}
