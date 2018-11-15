<?php

namespace App\Controller;

use App\Service\ActuatorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class MainController
{
    /**
     * @var ActuatorService
     */
    private $actuatorService;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(ActuatorService $actuatorService, EntityManagerInterface $em)
    {
        $this->actuatorService = $actuatorService;
        $this->em = $em;
    }

    public function getValue()
    {
        $controller = $this->em->find('App\\Entity\\Controller', 1);
        $type = $this->em->find('App\\Entity\\ActuatorType', 1);
        $element = $this->em->find('App\\Entity\\Element', 1);
        if ($controller && $type && $element) {
            $this->actuatorService->addActuator($type, $controller, $element);
        }
        return new JsonResponse(['asdasd' => 'some ansver']);
    }
}