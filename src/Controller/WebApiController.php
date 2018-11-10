<?php
/**
 * Created by IntelliJ IDEA.
 * User: george
 * Date: 10.11.18
 * Time: 15:36
 */

namespace App\Controller;


use App\Entity\ObjectParameter;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class WebApiController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getUpdates(Request $request)
    {
        var_dump($request->getContent());
        /** @var ObjectParameter[] $parameter */
        $parameter = $this->em->createQueryBuilder()
            ->select('op')
            ->from(ObjectParameter::class, 'op')
            ->where('op.name = :name')
            ->setParameter('name', 'objectId')
            ->getQuery()
            ->getResult();
        if (\count($parameter) > 0) {
            $var = '12123123123';
            $rsm = new ResultSetMapping();
            $rsm->addScalarResult('result', 'result');
            $query = $this->em->createNativeQuery('SELECT match_object_hash(?) as result;', $rsm);
            $query->setParameter(1, $var);
            $res = (int)$query->getResult()[0]['result'];
            var_dump($res);
            if($res === 0){
                return new JsonResponse(['json' => ['updates' => 'has updates', 'object_id' => $parameter[0]->getValue()]], 200);
            }
            return new JsonResponse(['json' => ['updates' => 'no updates', 'object_id' => $parameter[0]->getValue()]], 200);
        }
        return new JsonResponse(['result' => 'error', 'message' => 'Object did not initialised.'], 200);
    }
}