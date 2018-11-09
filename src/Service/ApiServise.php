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
use GuzzleHttp\Client;
use PhpParser\Error;

class ApiServise
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

    public function __construct(string $domain, string $uriPrefix, string $token, string $accountToken, EntityManagerInterface $em)
    {
        $this->uriPrefix = $uriPrefix;
        $this->token = $token;
        $this->accountToken = $accountToken;

        $this->client = new Client([
            'base_uri' => $domain,
            'timeout' => 2.0
        ]);
        $this->em = $em;
    }

    public function initObject(string $title, string $address): int
    {
        $response = $this->client->post($this->uriPrefix . '/main/initObject', [
            'json' => [
                'account_token' => $this->accountToken,
                'token' => $this->token,
                'object_title' => $title,
                'object_adress' => $address,
            ]
        ]);

        $statusCode = $response->getStatusCode();

        if ($statusCode !== 200) {
            throw new Error('Server responded with status ' . $statusCode . '. Error message: ' . $response->getBody()->getContents());
        }
        $content = $response->getBody()->getContents();
        $objectIdFromServer = json_decode($content, true)['objectId'];
        if($objectIdFromServer === null || $objectIdFromServer === false){
            throw new Error('Server does not return an object id. Or error occurred.');
        }
        $idParameter = $this->em->createQueryBuilder()
            ->select('op')
            ->from(ObjectParameter::class, 'op')
            ->where('op.name = :name')
            ->setParameter('name', 'objectId')
            ->getQuery()
            ->getResult();
        if (\count($idParameter) === 0) {
            $idParameter = new ObjectParameter('objectId', '' . json_decode($response->getBody()->getContents())['objectId']);
            $this->em->persist($idParameter);
        } else {
            $this->em->createQueryBuilder()
                ->update(ObjectParameter::class, 'op')
                ->where('op.name = \'objectId\'')
                ->set('op.value', "'$objectIdFromServer'")
                ->getQuery()
                ->execute();
        }
        $this->em->flush();
        return (int)$objectIdFromServer;
    }

    public function syncStructure(int $objectId) {
        $response = $this->client->post($this->uriPrefix . '/main/syncStructure', [
            'json' => [
                'account_token' => $this->accountToken,
                'token' => $this->token,
                'object_id' => $objectId,
            ]
        ]);

        $statusCode = $response->getStatusCode();

        if ($statusCode !== 200) {
            throw new Error('Server responded with status ' . $statusCode . '. Error message: ' . $response->getBody()->getContents());
        }

        $content = $response->getBody()->getContents();
        $data = json_decode($content, true)['objectId'];
        if($data === null || $data === false){
            throw new Error('Server does not return a data. Or error occurred.');
        }

        foreach($data['actuator_type'] as $actuatorTypeData){
            $actuatorType = new ActuatorType($actuatorTypeData['id'], $actuatorTypeData['title']);
            $this->em->persist($actuatorType);
        }
        foreach($data['event_type'] as $eventTypeData){
            $eventType = new EventType($eventTypeData['id']);
            $this->em->persist($eventType);
        }
        foreach($data['element_type'] as $elementTypeData){
            $elementType = new ElementType($elementTypeData['id'], $elementTypeData['title']);
            $this->em->persist($elementType);
        }
        foreach($data['sensor_type'] as $sensorTypeData){
            $sensorType = new SensorType($sensorTypeData['id'], $sensorTypeData['title']);
            $this->em->persist($sensorType);
        }
        foreach($data['trigger'] as $triggerData){
            $trigger = new Trigger($triggerData['id'], $triggerData['status'], $triggerData['content']);
            $this->em->persist($trigger);
        }
        $this->em->flush();
    }
}