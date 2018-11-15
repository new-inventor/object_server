<?php


namespace App\Controller;


use App\Api\Error\ApiError;
use App\Entity\ObjectParameter;
use App\Service\DevisesService;
use App\Service\WebServerApiService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Log\Logger;

class WebApiController extends AbstractController
{
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
            function (EntityManagerInterface $em, Request $request) {
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
            $this->em,
            $request
        );
    }


    public function deviceAction(string $action, WebServerApiService $apiService, DevisesService $devisesService)
    {
        return $this->tryToHandle(
            function (string $action, WebServerApiService $apiService, DevisesService $devisesService) {
                if ($action === 'link') {

                } elseif ($action === 'unlink') {

                } elseif ($action === 'list') {
                    return $this->getResponse($devisesService->all());
                }
                throw new ApiError("Unknown devise actoin '$action'");
            },
            $action,
            $apiService,
            $devisesService
        );
    }

    public function getStatus(string $peripheralType)
    {
        return $this->tryToHandle(
            function (string $peripheralType) {
                if ($peripheralType === 'sensor') {

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
}