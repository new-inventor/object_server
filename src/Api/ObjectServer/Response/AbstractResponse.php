<?php


use Symfony\Component\HttpFoundation\JsonResponse;

class AbstractResponse
{
    public function toResponse()
    {
        return new JsonResponse([], 200);
    }
}