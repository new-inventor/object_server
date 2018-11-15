<?php


namespace App\Service;


use GuzzleHttp\Client;

class AbstractApiService
{
    /**
     * @var Client
     */
    private $client;
    /**
     * @var string
     */
    private $token;
    /**
     * @var string
     */
    private $accountToken;

    public function __construct(
        string $domain,
        string $uriPrefix,
        Client $client,
        string $token,
        string $accountToken
    ) {
        $this->client = $client;
        $this->token = $token;
        $this->accountToken = $accountToken;
    }
}