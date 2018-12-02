<?php


namespace App\Controller;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class LocalApiController
{
    public function controllerInit(Request $request)
    {

    }

    public function addLog(Request $request, string $peripheralType, string $type){
        var_dump($peripheralType);
        var_dump($type);
        var_dump($request->getContent());
        return new JsonResponse([]);
    }

    public function registerController(Request $request)
    {

    }

    public function updateController(Request $request)
    {

    }
}