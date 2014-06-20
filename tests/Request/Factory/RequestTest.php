<?php

namespace Icambridge\Tests\Http\Request\Factory;

use Icambridge\Tests\Http\TestCase;
use Icambridge\Http\Request\Factory\RequestFactory;

class RequestTest extends TestCase
{
    public function testReturnsARequestInstance()
    {
        $data = [
            'method' => 'GET',
            'request_url' => ['path' => '/test'],
            'version' => '1.0',
            'headers' => []
        ];

        $factory = new RequestFactory();
        $request = $factory->get($data);
        $this->assertInstanceOf('Icambridge\Http\Request\Request', $request);
    }

    public function testRequestHasInfo()
    {
        $method = 'GET';
        $uri = '/test';
        $version = '1.0';
        $headers = [];

        $data = [
            'method' => $method,
            'request_url' => ['path' => $uri, 'query' => null],
            'version' => $version,
            'headers' => $headers
        ];

        $factory = new RequestFactory();
        $request = $factory->get($data);

        $this->assertEquals($method, $request->getMethod());
        $this->assertEquals($uri, $request->getPath());
        $this->assertEquals($version, $request->getHttpVersion());
    }

    public function testRequestFailsGracefullyWithEmptyQuery()
    {
        $method = 'GET';
        $uri = '/test';
        $version = '1.0';
        $headers = [];
        $keyOne = 'test';
        $valueOne = 'value';
        $keyTwo = 'testing';
        $valueTwo = 'again';
        $query = "{$keyOne}={$valueOne}&{$keyTwo}={$valueTwo}";

        $data = [
            'method' => $method,
            'request_url' => ['path' => $uri, 'query' => $query],
            'version' => $version,
            'headers' => $headers
        ];

        $factory = new RequestFactory();
        $request = $factory->get($data);
        $this->assertInstanceOf('Icambridge\Http\Request\Request', $request);
        $this->assertArrayHasKey($keyOne, $request->getQuery());
        $this->assertArrayHasKey($keyTwo, $request->getQuery());
        $this->assertEquals($valueOne, $request->getQuery()[$keyOne]);
        $this->assertEquals($valueTwo, $request->getQuery()[$keyTwo]);
    }
}
