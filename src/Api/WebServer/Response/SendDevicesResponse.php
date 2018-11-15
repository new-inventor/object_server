<?php


namespace App\Api\WebServer\Response;

class SendDevicesResponse extends AbstractResponse
{
    private $success;

    /**
     * @return mixed
     */
    public function isSuccess()
    {
        return $this->success;
    }

    /**
     * @param array|null $response
     */
    protected function mapResponse($response): void
    {
        $this->success = true;
    }
}