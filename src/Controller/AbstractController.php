<?php


namespace App\Controller;


use App\Api\Error\ApiError;
use Symfony\Component\HttpFoundation\JsonResponse;

class AbstractController
{
    public function tryToHandle($handler, ...$parameters)
    {
        try {
            return $handler(...$parameters);
        } catch (\Throwable $e) {
            return $this->getErrorResponse($e);
        }
    }

    public function parseJson(string $json)
    {
        $res = json_decode($json, true);
        if (\strlen($json) !== 4 && $res === null) {
            throw new ApiError('Invalid json data.');
        }
        return $res;
    }

    public function getErrorResponse(\Throwable $e)
    {
        return new JsonResponse([
            'result' => 'error',
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 200);
    }

    public function getResponse($result)
    {
        return new JsonResponse(['json' => $result], 200);
    }
}