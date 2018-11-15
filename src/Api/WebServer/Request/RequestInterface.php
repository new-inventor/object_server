<?php


namespace App\Api\WebServer\Request;

interface RequestInterface
{
    /**
     * @return bool
     */
    public function isUseAccountToken(): bool;

    /**
     * @return string
     */
    public function getUri(): string;

    /**
     * @return array
     */
    public function toPostArray(): array;
}