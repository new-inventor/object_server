<?php


namespace App\Controller;


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