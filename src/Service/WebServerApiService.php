<?php


namespace App\Service;


use App\Api\WebServer\Request\GetTypesRequest;
use App\Api\WebServer\Request\InitObjectRequest;
use App\Api\WebServer\Request\RequestInterface;
use App\Api\WebServer\Request\ResetIpRequest;
use App\Api\WebServer\Response\GetTypesResponse;
use App\Api\WebServer\Response\InitObjectResponse;
use App\Api\WebServer\Response\ResetIpResponse;
use App\Entity\Actuator;
use App\Entity\ActuatorType;
use App\Entity\Controller;
use App\Entity\EventTrigger;
use App\Entity\EventType;
use App\Entity\ObjectParameter;
use App\Entity\Sensor;
use App\Entity\SensorType;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class WebServerApiService
{
    /**
     * @var string
     */
    private $domain;
    /**
     * @var string
     */
    private $uriPrefix;
    /**
     * @var string
     */
    private $token;
    /**
     * @var string
     */
    private $accountToken;
    /**
     * @var Client
     */
    private $client;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(
        string $domain,
        string $uriPrefix,
        string $accountToken,
        EntityManagerInterface $em
    ) {
        $this->uriPrefix = $uriPrefix;
        $this->em = $em;
        /** @var ObjectParameter[] $objectToken */
        $objectToken = $this->em->createQueryBuilder()
            ->select('op')
            ->from(ObjectParameter::class, 'op')
            ->where('op.name = :name')
            ->setParameter('name', 'objectToken')
            ->getQuery()
            ->getResult();
        if (\count($objectToken) > 0) {
            $this->token = $objectToken[0]->getValue();
        } else {
            $this->token = '';
        }
        $this->accountToken = $accountToken;

        $this->client = new Client([
            'base_uri' => $domain,
            'timeout' => 2.0
        ]);
    }

    public function initObject(string $title, string $address): int
    {
        $request = new InitObjectRequest($title, $address);
        $response = new InitObjectResponse($this->getApiResponse($request));
        $this->createOrUpdateParameter('objectId', $response->getObjectId());
        $this->createOrUpdateParameter('objectToken', $response->getObjectToken());
        $this->em->flush();
        $this->em->clear();
        return $response->getObjectId();
    }

    public function getApiResponse(RequestInterface $request): ResponseInterface
    {
        return $this->client->post(
            $this->uriPrefix . $request->getUri(),
            ['form_params' => $this->initParameters($request->toPostArray(), $request->isUseAccountToken())]
        );
    }

    public function initParameters($mainParams, bool $useAccountToken = false)
    {
        $params = [
            'json' => json_encode($mainParams),
        ];
        if ($this->token !== '') {
            $params['token'] = $this->token;
        }

        if ($useAccountToken) {
            $params['account_token'] = $this->accountToken;
        }

        return $params;
    }

    public function createOrUpdateParameter(string $name, $value)
    {
        $parameter = $this->em->createQueryBuilder()
            ->select('op')
            ->from(ObjectParameter::class, 'op')
            ->where('op.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getResult();
        if (\count($parameter) === 0) {
            $parameter = new ObjectParameter($name, '' . $value);
            $this->em->persist($parameter);
        } else {
            $this->em->createQueryBuilder()
                ->update(ObjectParameter::class, 'op')
                ->where('op.name = \'' . $name . '\'')
                ->set('op.value', "'$value'")
                ->getQuery()
                ->execute();
        }
    }

    public function syncTypes()
    {
        $request = new GetTypesRequest();
        $response = new GetTypesResponse($this->getApiResponse($request));
        $this->createOrUpdateButch(SensorType::class, $response->getSensorTypes());
        $this->createOrUpdateButch(ActuatorType::class, $response->getActuatorTypes());
        $this->createOrUpdateButch(EventType::class, $response->getEventTypes());
        $this->createOrUpdateButch(EventTrigger::class, $response->getTriggers());
        $this->em->flush();
    }

    protected function createOrUpdateButch(string $entity, $list)
    {
        foreach ($list as $item) {
            $this->createOrUpdateField($entity, (int)$item['id'], $item);
        }
    }

    public function createOrUpdateField(string $entity, int $id, array $params)
    {
        $oldEntity = $this->em->find($entity, $id);
        if ($oldEntity === null) {
            $oldEntity = new $entity(...$params);
        } else {
            $oldEntity->load($params);
        }
        $this->em->persist($oldEntity);
        return $oldEntity;
    }

}