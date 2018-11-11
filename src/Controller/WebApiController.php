<?php
/**
 * Created by IntelliJ IDEA.
 * User: george
 * Date: 10.11.18
 * Time: 15:36
 */

namespace App\Controller;


use App\Entity\ObjectParameter;
use App\Service\ApiService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Log\Logger;

class WebApiController
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
        /** @var ObjectParameter[] $parameter */
        $parameter = $this->em->createQueryBuilder()
            ->select('op')
            ->from(ObjectParameter::class, 'op')
            ->where('op.name = :name')
            ->setParameter('name', 'objectId')
            ->getQuery()
            ->getResult();
        if (\count($parameter) > 0) {
            $requestBody = json_decode($request->getContent());
            $rsm = new ResultSetMapping();
            $rsm->addScalarResult('result', 'result');
            $query = $this->em->createNativeQuery('SELECT match_object_hash(?) as result;', $rsm);
            $query->setParameter(1, $requestBody['object_hash']);
            $res = (int)$query->getResult()[0]['result'];
            if($res === 0){
                exec(__DIR__. '/../../bin/console app:send-devices');
                return new JsonResponse(['json' => ['updates' => 'has updates', 'object_id' => $parameter[0]->getValue()]], 200);

            }
            return new JsonResponse(['json' => ['updates' => 'no updates', 'object_id' => $parameter[0]->getValue()]], 200);
        }
        return new JsonResponse(['result' => 'error', 'message' => 'Object did not initialised.'], 200);
    }

    public function getDevices(ApiService $apiService)
    {
        return new JsonResponse(['json' => $apiService->getDevises()]);
    }

    public function getActuatorLog()
    {
        return new JsonResponse(['json' => []]);
    }
}