<?php


namespace App\Controller;


use App\Api\Error\ApiError;
use App\Entity\ObjectParameter;
use App\Service\DevisesService;
use App\Service\ElementsService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Log\Logger;

class WebApiController extends AbstractController
{
    private $controllerHost = 'http://192.168.0.110:8001/';
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var Logger
     */
    private $logger;

    public function __construct(EntityManagerInterface $em, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->logger = $logger;
    }

    public function hasUpdates(Request $request)
    {
        $this->logger->log('600', 'Has updates called.', ['GET' => $request, 'POST' => $request->getContent()]);
        return $this->tryToHandle(
            function (Request $request) {
                /** @var ObjectParameter[] $parameter */
                $parameter = $this->em->createQueryBuilder()
                    ->select('op')
                    ->from(ObjectParameter::class, 'op')
                    ->where('op.name = :name')
                    ->setParameter('name', 'objectId')
                    ->getQuery()
                    ->getResult();
                if (\count($parameter) === 0) {
                    throw new ApiError('Object did not initialised.');
                }
                $requestBody = json_decode($request->getContent());
                $rsm = new ResultSetMapping();
                $rsm->addScalarResult('result', 'result');
                $query = $this->em->createNativeQuery('SELECT match_object_hash(?) as result;', $rsm);
                $query->setParameter(1, $requestBody['object_hash']);
                $res = (int)$query->getResult()[0]['result'];
                $response = [
                    'updates' => $res === 0 ? 'has updates' : 'no updates',
                    'object_id' => $parameter[0]->getValue()
                ];
                if ($res === 0) {
                    exec(__DIR__ . '/../../bin/console app:send-devices');
                }
                return $this->getResponse($response);
            },
            $request
        );
    }


    public function deviceAction(string $action, DevisesService $devisesService)
    {
        return $this->tryToHandle(
            function (string $action, DevisesService $devisesService) {
                if ($action === 'link') {

                } elseif ($action === 'unlink') {

                } elseif ($action === 'list') {
                    return $this->getResponse($devisesService->all());
                }
                throw new ApiError("Unknown devise actoin '$action'");
            },
            $action,
            $devisesService
        );
    }

    public function getStatus(string $peripheralType)
    {
        return $this->tryToHandle(
            function (string $peripheralType) {
                if ($peripheralType === 'sensor') {
                    return $this->getResponse([
                        'elements' => [
                            ''
                        ]
                    ]);
                } elseif ($peripheralType === 'actuator') {

                } elseif ($peripheralType === 'controller') {

                } elseif ($peripheralType === 'element') {

                } elseif ($peripheralType === 'room') {

                } elseif ($peripheralType === 'object') {

                }
                throw new ApiError("Invalid peripheral type '$peripheralType'.");
            },
            $peripheralType
        );
    }

    public function getElementCurrentData(Request $request, ElementsService $elementsService)
    {
        $client  = new Client();
        return $this->tryToHandle(
            function (Request $request, ElementsService $elementsService) use ($client) {
                $parsedParams = $this->parseJson($request->request->get('json'));
                $sensors = $elementsService->getElementCurrentData($parsedParams['element_id']);
                foreach($sensors['sensor'] as $id => $value) {
                    $level = $value['level'];
                    if($id === 96){
                        $res = $client->post($this->controllerHost . 'actuator/1/' . ($level === 0 ? 'HIGH' : 'LOW'));
                        if($res->getStatusCode() !== 200) {
                            return $this->getResponse($level);
                        }
                    }
                    if($id === 92 || $id === 93){
                        $res = $client->post($this->controllerHost . 'actuator/0/' . ($level === 0 ? 'HIGH' : 'LOW'));
                    }
                    if($id === 100){
                        $res = $client->post($this->controllerHost . 'actuator/2/' . ($level === 0 ? 'HIGH' : 'LOW'));
                    }
                }
                return $this->getResponse($elementsService->getElementCurrentData($parsedParams['element_id']));
            },
            $request,
            $elementsService
        );
    }

    public function getSensorCurrentData(Request $request, ElementsService $elementsService)
    {
        $client  = new Client();
        return $this->tryToHandle(
            function (Request $request, ElementsService $elementsService) use ($client){
                $parsedParams = $this->parseJson($request->request->get('json'));
                if ($parsedParams['item'] === 'sensor') {
                    $item = $elementsService->getSensorCurrentData($parsedParams['item_id']);
                    $level = $item['level'];
                    if('' . $parsedParams['item_id'] === '92'){
                        $res = $client->post($this->controllerHost . 'actuator/0/' . ($level > 1 ? 'LOW' : 'HIGH'));
                        if($res->getStatusCode() !== 200) {
                            return $this->getResponse($level);
                        }
                    }
                    if('' . $parsedParams['item_id'] === '96' || ''. $parsedParams['item_id'] === '93'){
                        $client->post($this->controllerHost . 'actuator/1/' . ($level > 1 ? 'LOW' : 'HIGH'));
                    }
                    if('' . $parsedParams['item_id'] === '100'){
                        $client->post($this->controllerHost . 'actuator/2/' . ($level > 1 ? 'LOW' : 'HIGH'));
                    }
                    return $this->getResponse($item);
                } elseif ($parsedParams['item'] === 'actuator') {
                    return $this->getResponse([]);
                }
                throw new ApiError('Invalid item.');
            },
            $request,
            $elementsService
        );
    }

    public function getRoomCurrentData(Request $request, ElementsService $elementsService)
    {
        return $this->tryToHandle(
            function (Request $request, ElementsService $elementsService) {
                $parsedParams = $this->parseJson($request->request->get('json'));
                return $this->getResponse($elementsService->getRoomCurrentData($parsedParams['room_id']));
            },
            $request,
            $elementsService
        );
    }

    public function getElementLog(Request $request, ElementsService $elementsService)
    {
        return $this->tryToHandle(
            function (Request $request, ElementsService $elementsService) {
                $parsedParams = $this->parseJsonBody($request);
                $from = $parsedParams['from'] ?? null;
                if ($from === null) {
                    $from = (new \DateTime())->sub(new \DateInterval('PT30M'));
                }
                $to = $parsedParams['to'] ?? null;
                if ($to === null) {
                    $to = new \DateTime();
                }
                return $this->getResponse([]);
            },
            $request,
            $elementsService
        );
    }

    public function syncLog(string $peripheralType, string $logType)
    {
        return $this->tryToHandle(
            function (string $peripheralType, string $logType) {
                if ($peripheralType === 'sensor') {
                    if ($logType === 'int') {

                    } elseif ($logType === 'bit') {

                    }
                    throw new ApiError("Invalid log type '$logType'.");
                } elseif ($peripheralType === 'actuator') {
                    if ($logType === 'int') {

                    } elseif ($logType === 'bit') {

                    }
                    throw new ApiError("Invalid log type '$logType'.");
                } elseif ($peripheralType === 'controller') {

                } elseif ($peripheralType === 'element') {

                } elseif ($peripheralType === 'room') {

                } elseif ($peripheralType === 'object') {

                }
                throw new ApiError("Invalid peripheral type '$peripheralType'.");
            },
            $peripheralType,
            $logType
        );
    }

    public function updateStructure(Request $request)
    {
        return $this->tryToHandle(
            function (Request $request) {
                $content = json_decode($request->getContent());
                return $this->getResponse([]);
            },
            $request
        );
    }

    public function actuatorSet(int $actuator_id, int $level) {
        $actuator_id--;
        $client = new Client();
        $levelName = $level > 0 ? 'HIGH' : 'LOW';
        $result = $client->post($this->controllerHost . 'actuator/' . $actuator_id . '/' . $levelName);
        if($result->getStatusCode() === 200){
            return $this->getResponse(['succes' => 'true', 'actuator_id' => $actuator_id, 'level' => $level]);
        }
        return $this->getResponse(['succes' => 'false', 'actuator_id' => $actuator_id, 'level' => $level]);
    }
}