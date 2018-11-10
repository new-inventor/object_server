<?php
/**
 * Created by IntelliJ IDEA.
 * User: george
 * Date: 09.11.18
 * Time: 22:18
 */

namespace App\Service;


use App\Entity\Actuator;
use App\Entity\ActuatorType;
use App\Entity\ElementType;
use App\Entity\EventType;
use App\Entity\ObjectParameter;
use App\Entity\SensorType;
use App\Entity\Trigger;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use GuzzleHttp\Client;
use PhpParser\Error;

class ApiService
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
        string $token,
        string $accountToken,
        EntityManagerInterface $em
    ) {
        $this->uriPrefix = $uriPrefix;
        $this->token = $token;
        $this->accountToken = $accountToken;

        $this->client = new Client([
            'base_uri' => $domain,
            'timeout' => 2.0
        ]);
        $this->em = $em;
    }

    protected function makeApiRequest(
        string $path,
        $mainParams,
        $useAccountToken
    ) {
        $params = [
            'json' => json_encode($mainParams),
            'token' => $this->token,
        ];

        if ($useAccountToken) {
            $params['account_token'] = $this->accountToken;
        }

        $response = $this->client->post($this->uriPrefix . $path, ['form_params' => $params]);
        $statusCode = $response->getStatusCode();

        if ($statusCode !== 200) {
            throw new Error('Server responded with status ' . $statusCode . '. Error message: ' . $response->getBody()->getContents());
        }

        return $response->getBody()->getContents();
    }

    public function initObject(string $title, string $address): int
    {
        $content = $this->makeApiRequest('/state/initObject', [
            'object_title' => $title,
            'object_adress' => $address
        ], true);
        $content = json_decode($content, true);
        var_dump($content);
        $objectId = $content['object']['object_id'];
        $objectToken = $content['object']['object_token'];
        if ($objectId === null || $objectId === false) {
            throw new Error('Server does not return an object id. Or error occurred.');
        }
        $this->createOrUpdateParameter('objectId', $objectId);
        $this->createOrUpdateParameter('objectToken', $objectToken);
        $this->em->flush();
        $this->em->clear();
        var_dump($objectId, $objectToken);
        die();
        return (int)$objectId;
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

    public function syncStructure(int $objectId)
    {
        $content = $this->makeApiRequest('/objects/syncStructure', [
            'object_id' => $objectId,
        ], false);
        $data = json_decode($content, true)['objectId'];
        if ($data === null || $data === false) {
            throw new Error('Server does not return a data. Or error occurred.');
        }

        foreach ($data['actuator_type'] as $actuatorTypeData) {
            $actuatorType = new ActuatorType($actuatorTypeData['id'], $actuatorTypeData['title']);
            $this->em->persist($actuatorType);
        }
        $this->em->flush();
        $this->em->clear();
        foreach ($data['event_type'] as $eventTypeData) {
            $eventType = new EventType($eventTypeData['id']);
            $this->em->persist($eventType);
        }
        $this->em->flush();
        $this->em->clear();
        foreach ($data['element_type'] as $elementTypeData) {
            $elementType = new ElementType($elementTypeData['id'], $elementTypeData['title']);
            $this->em->persist($elementType);
        }
        $this->em->flush();
        $this->em->clear();
        foreach ($data['sensor_type'] as $sensorTypeData) {
            $sensorType = new SensorType($sensorTypeData['id'], $sensorTypeData['title']);
            $this->em->persist($sensorType);
        }
        $this->em->flush();
        $this->em->clear();
        foreach ($data['trigger'] as $triggerData) {
            $trigger = new Trigger($triggerData['id'], $triggerData['status'], $triggerData['content']);
            $this->em->persist($trigger);
        }
        $this->em->flush();
        $this->em->clear();
    }


}