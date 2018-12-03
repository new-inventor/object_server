<?php


namespace App\Controller;


use App\Api\WebServer\Request\SensorValueForEventRequest;
use App\Entity\Sensor;
use App\Entity\SensorIntLog;
use App\Service\ElementsService;
use App\Service\WebServerApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class LocalApiController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * LocalApiController constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function controllerInit(Request $request)
    {

    }

    public function addLog(WebServerApiService $apiService, Request $request, string $peripheralType, string $type, ElementsService $elementsService)
    {
        $data = json_decode($request->getContent(), true);
        if ($peripheralType === 'sensor') {
            if ($type === 'int') {
                foreach ($data as $id => $values) {
                    $values = array_values($values);
                    $value = $id === 91 || $id === 96 ? (int)$values[0] : ((int)$values[0] + (int)$values[1]) / 2;
                    $level = $elementsService->getSensorLevel($id, $value);
                    if($level > 0) {
                        $request = new SensorValueForEventRequest($id, $value, $level);
                        $apiService->getApiResponse($request);
                    }
                    $sensor = $this->em->find(Sensor::class, $id);
                    $record = new SensorIntLog($value, $sensor);
                    $this->em->persist($record);
                    $elementsService->runTrigger($id, $value);
                }
                $this->em->flush();
            }
        }
        return new JsonResponse([]);
    }

    public function registerController(Request $request)
    {

    }

    public function updateController(Request $request)
    {

    }
}