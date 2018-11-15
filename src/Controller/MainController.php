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

    public function main()
    {
        return new JsonResponse(null);
    }
}