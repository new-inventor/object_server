<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

class MainController {
	public function getValue() {
		return new JsonResponse(['asdasd' => 'some ansver']);
	}
}