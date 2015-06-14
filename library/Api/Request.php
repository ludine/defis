<?php

namespace Genedys\Api;


use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

class Request
{
    const TOKEN = '97c7c094ddecac41090d47b5dfb66fab';

    /** @var Client */
    private $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param $uri
     * @return mixed
     */
    public function get($uri)
    {
        /** @var Response $response */
        $response = $this->client->get($uri, [
            'headers' => [
                'ApiToken' => self::TOKEN,
            ],
        ]);

        if ($response->getStatusCode() == 200) {
            return $response->getBody()->getContents();
        }

        return false;
    }

    /**
     * @param $uri
     * @param $data
     * @return bool|string
     */
    public function post($uri, $data)
    {
        $response = $this->client->post($uri, [
            'headers' => [
                'ApiToken' => self::TOKEN
            ],
            'body' => $data
        ]);

        if ($response->getStatusCode() == 200) {
            return $response->getBody()->getContents();
        }

        return false;
    }
}