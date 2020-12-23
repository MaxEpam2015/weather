<?php

namespace App\Api;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

class Client
{
    /**
     * @var GuzzleClient
     */
    private $guzzleClient;


    /**
     * Client constructor.
     * @param GuzzleClient $guzzleClient
     */
    public function __construct(GuzzleClient $guzzleClient)
    {
        $this->guzzleClient = $guzzleClient;
    }

    /**
     * @param string $method
     * @param string $uri
     * @param null|string $payload
     * @return array|null
     */
    public function request(string $method, string $uri): ?array
    {
        $response = null;
        try {
            $response = $this->guzzleClient->request($method, $uri, ['http_errors' => false]);
        } catch (GuzzleException $exception) {
            throw new \Exception($exception->getMessage());
        }
        if (!$response || !$response->getBody()) {
            throw new \Exception('Empty response');
        }

        return json_decode($response->getBody(), 'true') ?: null;
    }
}
