<?php

namespace Icambridge\Http\Request;

use Evenement\EventEmitter;
use Guzzle\Parser\Message\MessageParserInterface;
use Icambridge\Http\Request\Factory\RequestFactoryInterface;


/**
 * @event headers
 * @event error
 */
class Parser extends EventEmitter implements ParserInterface
{
    private $buffer = '';

    private $maxSize = 4096;

    /**
     * @var MessageParserInterface
     */
    private $parser;

    /**
     * @var RequestFactoryInterface
     */
    private $factory;

    function __construct(RequestFactoryInterface $factory, MessageParserInterface $parser)
    {
        $this->factory = $factory;
        $this->parser  = $parser;
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
        $parsed = $this->parser->parseRequest($data);

        $bodyBuffer = (isset($parsed['body'])) ? $parsed['body'] : "";
        $request = $this->factory->get($parsed);

        return array($request, $bodyBuffer);
    }
}
