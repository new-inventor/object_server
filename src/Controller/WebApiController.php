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
use Symfony\Component\HttpFoundation\JsonResponse;

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

    public function getUpdates()
    {
        /** @var ObjectParameter[] $parameter */
        $parameter = $this->em->createQueryBuilder()
            ->select('op')
            ->from(ObjectParameter::class, 'op')
            ->where('op.name = :name')
            ->setParameter('name', 'objectId')
            ->getQuery()
            ->getResult();
        if (\count($parameter) > 0) {
            return new JsonResponse(['json' => ['updates' => 'no updates', 'object_id' => $parameter[0]->getValue()]],
                304);
        }
        return new JsonResponse(['result' => 'error', 'message' => 'Object did not initialised.'], 424);
    }
}