<?php

namespace Icambridge\Tests\Http;

use Icambridge\Http\Server;

class ServerTest extends TestCase
{
    protected $server;

    protected $io;

    public function setUp()
    {
        $requestParser = new ParserStub();
        $this->io = new ServerStub();

        $this->server = new Server($this->io, $requestParser);
    }

    public function testRequestEventIsEmitted()
    {

        $this->server->on('request', $this->expectCallableOnce());

        $conn = new ConnectionStub();
        $this->io->emit('connection', array($conn));

        $data = $this->createGetRequest();
        $conn->emit('data', array($data));
    }

    public function testRequestEvent()
    {
        $i = 0;

        $this->server->on('request', function ($request, $response) use (&$i) {
            $i++;

            $this->assertInstanceOf('Icambridge\Http\Request\Request', $request);
            $this->assertSame('/', $request->getPath());
            $this->assertSame('GET', $request->getMethod());
            $this->assertSame('127.0.0.1', $request->remoteAddress);

            $this->assertInstanceOf('Icambridge\Http\Response', $response);
        });

        $conn = new ConnectionStub();
        $this->io->emit('connection', array($conn));

        $data = $this->createGetRequest();
        $conn->emit('data', array($data));

        $this->assertSame(1, $i);
    }

    public function testResponseContainsPoweredByHeader()
    {

        $this->server->on('request', function ($request, $response) {
            $response->writeHead();
            $response->end();
        });

        $conn = new ConnectionStub();
        $this->io->emit('connection', array($conn));

        $data = $this->createGetRequest();
        $conn->emit('data', array($data));

        $this->assertContains("\r\nX-Powered-By: React/alpha\r\n", $conn->getData());
    }

    private function createGetRequest()
    {
        $data = "GET / HTTP/1.1\r\n";
        $data .= "Host: example.com:80\r\n";
        $data .= "Connection: close\r\n";
        $data .= "\r\n";

        return $data;
    }
}
