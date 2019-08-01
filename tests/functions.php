<?php

namespace Omnipay\PaymentSense\Test\Gateway;

use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\Response;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

const HEADER_REGEX = "(^([^()<>@,;:\\\"/[\]?={}\x01-\x20\x7F]++):[ \t]*+((?:[ \t]*+[\x21-\x7E\x80-\xFF]++)*+)[ \t]*+\r?\n)m";
const HEADER_FOLD_REGEX = "(\r?\n[ \t]++)";

/**
 * Parses a request message string into a request object.
 *
 * @param string $message Request message string.
 *
 * @return Request
 */
function parse_request($message): array
{
    $data = _parse_message($message);
    $matches = [];
    if (!preg_match('/^[\S]+\s+([a-zA-Z]+:\/\/|\/).*/', $data['start-line'],
        $matches)) {
        throw new \InvalidArgumentException('Invalid request string');
    }
    $parts = explode(' ', $data['start-line'], 3);
    $version = isset($parts[2]) ? explode('/', $parts[2])[1] : '1.1';

    $attributes = [
        'method' => $parts[0],
        'uri' => $matches[1] === '/' ?
            _parse_request_uri($parts[1], $data['headers']) : $parts[1],
        'headers' => $data['headers'],
        'body' => $data['body'],
        'version' => $version,

    ];

    return $attributes;
}

/**
 * Parses a response message string into a response object.
 *
 * @param string $message Response message string.
 *
 * @return Response
 */
function parse_response($message)
{
    $data = _parse_message($message);
    // According to https://tools.ietf.org/html/rfc7230#section-3.1.2 the space
    // between status-code and reason-phrase is required. But browsers accept
    // responses without space and reason as well.
    if (!preg_match('/^HTTP\/.* [0-9]{3}( .*|$)/', $data['start-line'])) {
        throw new \InvalidArgumentException('Invalid response string: ' . $data['start-line']);
    }
    $parts = explode(' ', $data['start-line'], 3);

    return new Response(
        $parts[1],
        $data['headers'],
        $data['body'],
        explode('/', $parts[0])[1],
        isset($parts[2]) ? $parts[2] : null
    );
}

/**
 * Parses an HTTP message into an associative array.
 *
 * The array contains the "start-line" key containing the start line of
 * the message, "headers" key containing an associative array of header
 * array values, and a "body" key containing the body of the message.
 *
 * @param string $message HTTP request or response to parse.
 *
 * @return array
 * @internal
 */
function _parse_message($message)
{
    if (!$message) {
        throw new \InvalidArgumentException('Invalid message');
    }

    $message = ltrim($message, "\r\n");

    $messageParts = preg_split("/\r?\n\r?\n/", $message, 2);

    if ($messageParts === false || count($messageParts) !== 2) {
        throw new \InvalidArgumentException('Invalid message: Missing header delimiter');
    }

    list($rawHeaders, $body) = $messageParts;
    $rawHeaders .= "\r\n"; // Put back the delimiter we split previously
    $headerParts = preg_split("/\r?\n/", $rawHeaders, 2);

    if ($headerParts === false || count($headerParts) !== 2) {
        throw new \InvalidArgumentException('Invalid message: Missing status line');
    }

    list($startLine, $rawHeaders) = $headerParts;

    if (preg_match("/(?:^HTTP\/|^[A-Z]+ \S+ HTTP\/)(\d+(?:\.\d+)?)/i",
            $startLine, $matches) && $matches[1] === '1.0') {
        // Header folding is deprecated for HTTP/1.1, but allowed in HTTP/1.0
        $rawHeaders = preg_replace(HEADER_FOLD_REGEX, ' ',
            $rawHeaders);
    }

    /** @var array[] $headerLines */
    $count = preg_match_all(HEADER_REGEX, $rawHeaders, $headerLines,
        PREG_SET_ORDER);

    // If these aren't the same, then one line didn't match and there's an invalid header.
    if ($count !== substr_count($rawHeaders, "\n")) {
        // Folding is deprecated, see https://tools.ietf.org/html/rfc7230#section-3.2.4
        if (preg_match(HEADER_FOLD_REGEX, $rawHeaders)) {
            throw new \InvalidArgumentException('Invalid header syntax: Obsolete line folding');
        }

        throw new \InvalidArgumentException('Invalid header syntax');
    }

    $headers = [];

    foreach ($headerLines as $headerLine) {
        $headers[$headerLine[1]][] = $headerLine[2];
    }

    return [
        'start-line' => $startLine,
        'headers' => $headers,
        'body' => $body,
    ];
}

/**
 * Constructs a URI for an HTTP request message.
 *
 * @param string $path    Path from the start-line
 * @param array  $headers Array of headers (each value an array).
 *
 * @return string
 * @internal
 */
function _parse_request_uri($path, array $headers)
{
    $hostKey = array_filter(array_keys($headers), function ($k) {
        return strtolower($k) === 'host';
    });

    // If no host is found, then a full URI cannot be constructed.
    if (!$hostKey) {
        return $path;
    }

    $host = $headers[reset($hostKey)][0];
    $scheme = substr($host, -4) === ':443' ? 'https' : 'http';

    return $scheme . '://' . $host . '/' . ltrim($path, '/');
}

/**
 * Get a mock response for a client by mock file name
 *
 * @param string $path Relative path to the mock response file
 *
 * @return array|\Guzzle\Http\Message\Request|string
 */
function getMockHttpRequest($path)
{
    if (!file_exists(__DIR__ . '/Mock/' . $path) && file_exists(__DIR__ . '/../Mock/' . $path)) {
        return parse_request(file_get_contents(__DIR__ . '/../Mock/' . $path));
    }

    return parse_request(file_get_contents(__DIR__ . '/Mock/' . $path));
}