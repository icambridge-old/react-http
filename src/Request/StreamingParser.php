<?php

namespace Icambridge\Http\Request;

use Evenement\EventEmitter;
use Guzzle\Parser\Message\MessageParser;
use Icambridge\Http\Request\Factory\RequestFactoryInterface;

/**
 * @event headers
 * @event error
 */
class StreamingParser extends EventEmitter implements ParserInterface
{
    private $buffer = '';

    private $maxSize = 4096;

    /**
     * @var RequestFactoryInterface
     */
    private $factory;

    function __construct(RequestFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function feed($data)
    {
        if (strlen($this->buffer) + strlen($data) > $this->maxSize) {
            $this->emit('error', array(new \OverflowException("Maximum header size of {$this->maxSize} exceeded."), $this));

            return;
        }

        $this->buffer .= $data;

        if (false !== strpos($this->buffer, "\r\n\r\n")) {
            list($request, $bodyBuffer) = $this->parseRequest($this->buffer);

            $this->emit('headers', array($request, $bodyBuffer));
            $this->removeAllListeners();
        }
    }

    public function parseRequest($data)
    {
        list($headers, $bodyBuffer) = explode("\r\n\r\n", $data, 2);

        $parser = new MessageParser();
        $parsed = $parser->parseRequest($headers."\r\n\r\n");

        $request = $this->factory->get($parsed);

        return array($request, $bodyBuffer);
    }
}
