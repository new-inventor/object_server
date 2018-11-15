<?php


namespace App\Api\WebServer\Response;


use App\Api\Error\ApiError;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

class InitObjectResponse extends AbstractResponse
{
    /**
     * @var string
     */
    private $objectToken;
    /**
     * @var int
     */
    private $objectId;

    /**
     * @return string
     */
    public function getObjectToken(): string
    {
        return $this->objectToken;
    }

    /**
     * @return int
     */
    public function getObjectId(): int
    {
        return $this->objectId;
    }

    /**
     * @param array|null $response
     */
    protected function mapResponse($response): void
    {
        $this->objectId = (int)$response['object']['object_id'];
        $this->objectToken = $response['object']['object_token'];
    }

    /**
     * @param Response $response
     * @return mixed|null
     */
    protected function parseResponse(PsrResponseInterface $response)
    {
        $res = parent::parseResponse($response);
        if (!array_key_exists('object', $res)) {
            throw new ApiError('Invalid response. object parameter not found.');
        }
        return $res;
    }

}