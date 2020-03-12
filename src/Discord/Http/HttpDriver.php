<?php

namespace Discord\Http;

/**
 * Interface for HTTP drivers.
 *
 * @author David Cole <david@team-reflex.com>
 */
interface HttpDriver
{
    /**
     * Runs an HTTP request.
     *
     * @param string $method  The HTTP method to use.
     * @param string $url     The endpoint that will be queried.
     * @param array  $headers The headers to send in the request.
     * @param string $body    The request content.
     * @param array  $options An array of Guzzle options.
     *
     * @return \React\Promise\Promise
     */
    public function runRequest($method, $url, $headers, $body, array $options = []);
}
