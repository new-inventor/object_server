<?php


namespace App\Service;


use App\Api\WebServer\Request\GetElementsDataRequest;
use App\Api\WebServer\Request\GetTypesRequest;
use App\Api\WebServer\Request\InitObjectRequest;
use App\Api\WebServer\Request\RequestInterface;
use App\Api\WebServer\Response\GetElementsDataResponse;
use App\Api\WebServer\Response\GetTypesResponse;
use App\Api\WebServer\Response\InitObjectResponse;
use App\Entity\Actuator;
use App\Entity\ActuatorType;
use App\Entity\Element;
use App\Entity\ElementActuator;
use App\Entity\ElementSensor;
use App\Entity\ElementType;
use App\Entity\EventTrigger;
use App\Entity\EventType;
use App\Entity\ObjectParameter;
use App\Entity\Room;
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
    public $token;
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
            $item['id'] = (int)$item['id'];
            if(array_key_exists('status', $item)){
                $item['status'] = (int) $item['status'];
            }
            $this->createOrUpdateField($entity, $item['id'], $item);
        }
    }

    public function createOrUpdateField(string $entity, int $id, array $params)
    {
        $oldEntity = $this->em->find($entity, $id);
        if ($oldEntity === null) {
            $oldEntity = new $entity(...array_values($params));
        } else {
            $oldEntity->load($params);
        }
        $this->em->persist($oldEntity);
        return $oldEntity;
    }

    public function syncElements()
    {
        $request = new GetElementsDataRequest();
        $response = new GetElementsDataResponse($this->getApiResponse($request));
        $this->createOrUpdateButch(Room::class, $response->getRooms());
        $this->createOrUpdateButch(ElementType::class, $response->getElementTypes());
        $this->createOrUpdateButch(EventType::class, $response->getEventTypes());
        $this->em->flush();
        foreach ($response->getElements() as $item) {
            $oldEntity = $this->em->find(Element::class, $item['id']);
            $type = $this->em->find(ElementType::class, $item['element_type_id']);
            if(!$type){
                continue;
            }
            $room = $this->em->find(Room::class, $item['room_id']);
            if(!$room){
                continue;
            }
            if ($oldEntity === null) {
                $oldEntity = new Element(
                    $item['id'],
                    $type,
                    $room,
                    $item['parent_element_id']
                );
            } else {
                $oldEntity->setElementType($type);
                $oldEntity->setRoom($room);
                $oldEntity->setParentElementId($item['parent_element_id']);
            }
            $this->em->persist($oldEntity);
        }
        $this->em->flush();
        foreach ($response->getElementSensors() as $item) {
            $element = $this->em->find(Element::class, $item['element_id']);
            $sensor = $this->em->find(Sensor::class, $item['sensor_id']);
            if(!$element || !$sensor){
                continue;
            }
            $this->em->createQueryBuilder()->delete()->from(ElementSensor::class, 'es');
            $entity = new ElementSensor(
                $element,
                $sensor
            );
            $this->em->persist($entity);
        }
        foreach ($response->getElementActuators() as $item) {
            $this->em->createQueryBuilder()->delete()->from(ElementActuator::class, 'ea');
            $element = $this->em->find(Element::class, $item['element_id']);
            $actuator = $this->em->find(Actuator::class, $item['actuator_id']);
            if(!$element || !$actuator){
                continue;
            }
            $entity = new ElementActuator(
                $actuator,
                $element
            );
            $this->em->persist($entity);
        }
        $this->em->flush();
    }

}