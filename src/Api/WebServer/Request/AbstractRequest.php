<?php


namespace App\Api\WebServer\Request;


abstract class AbstractRequest implements RequestInterface
{
    protected $uri = '';
    protected $useAccountToken = false;

    /**
     * @return bool
     */
    public function isUseAccountToken(): bool
    {
        return $this->useAccountToken;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return array
     */
    public function toPostArray(): array
    {
        return [];
    }
}