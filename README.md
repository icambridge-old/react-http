# Http Component

[![Build Status](https://secure.travis-ci.org/icambridge/react-http.png?branch=master)](http://travis-ci.org/icambridge/react-http)

Library for building an evented http server. An experimental fork of [ReactPHP HTTP](https://github.com/reactphp/http).

## Usage

This is an HTTP server which responds with `Hello World` to every request.
```php
    $loop = React\EventLoop\Factory::create();
    $socket = new React\Socket\Server($loop);

    $requestFactory = new Icambridge\Http\Request\Factory\BodiedRequestFactory();
    $messageParser = new Guzzle\Parser\Message\MessageParser();
    $requestParser = new Icambridge\Http\Request\Parser($requestFactory, $messageParser);
    $http = new Icambridge\Http\Server($socket, $requestParser);

    $http->on('request', function ($request, $response) {
        $response->writeHead(200, array('Content-Type' => 'text/plain'));
        $response->end("Hello World!\n");
    });

    $socket->listen(1337);
    $loop->run();
```
