<?php
/**
 * Created by IntelliJ IDEA.
 * User: george
 * Date: 10.11.18
 * Time: 15:36
 */

namespace App\Controller;


use Symfony\Component\HttpFoundation\JsonResponse;

class WebApiController
{
    public function getUpdates() {
        return new JsonResponse(['json' => ['updates' => 'no updates']]);
    }
}