<?php


namespace App\Api\WebServer\Response;


use App\Api\Error\ApiError;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

abstract class AbstractResponse
{
    public function __construct(PsrResponseInterface $response)
    {
        $this->mapResponse($this->parseResponse($response));
    }

    /**
     * @param array | null $response
     */
    abstract protected function mapResponse($response): void;

    /**
     * @param Response $response
     * @return mixed|null
     */
    protected function parseResponse(PsrResponseInterface $response)
    {
        $res = json_decode($response->getBody()->getContents(), true);
        if ($res === false) {
            throw new ApiError('Can not parse the response.');
        }
        return $res;
    }

    /**
     * @param Response $response
     */
    protected function checkResponseStatus(PsrResponseInterface $response): void
    {
        $code = $response->getStatusCode();
        if ($code >= 400) {
            throw new ApiError("Api returns '$code' code. Error message: " . $response->getBody()->getContents(),
                $code);
        }
    }
}