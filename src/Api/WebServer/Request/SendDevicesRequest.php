<?php


namespace App\Api\WebServer\Request;


use App\Entity\Actuator;
use App\Entity\Controller;
use App\Entity\Sensor;

class SendDevicesRequest extends AbstractRequest
{
    protected $uri = '/objects/syncDevices';
    /**
     * @var Controller[]
     */
    private $controllers;
    /**
     * @var Sensor[]
     */
    private $sensors;
    /**
     * @var Actuator[]
     */
    private $actuators;

    /**
     * SendDevicesRequest constructor.
     * @param Controller[] $controllers
     * @param Sensor[] $sensors
     * @param Actuator[] $actuators
     */
    public function __construct(array $controllers, array $sensors, array $actuators)
    {
        $this->controllers = $controllers;
        $this->sensors = $sensors;
        $this->actuators = $actuators;
    }

    public function toPostArray(): array
    {
        return [
            'controller' => array_map(function (Controller $controller) {
                return [
                    'controller_id' => $controller->getId(),
                    'room_id' => $controller->getRoom()->getId(),
                ];
            }, $this->controllers),
            'sensor' => array_map(function (Sensor $sensor) {
                return [
                    'sensor_id' => $sensor->getId(),
                    'sensor_type_id' => $sensor->getSensorType()->getId(),
                    'controller_id' => $sensor->getController()->getId(),
                ];
            }, $this->sensors),
            'actuator' => array_map(function (Actuator $actuator) {
                return [
                    'actuator_id' => $actuator->getId(),
                    'actuator_type_id' => $actuator->getActuatorType()->getId(),
                    'controller_id' => $actuator->getController()->getId(),
                ];
            }, $this->actuators),
        ];
    }


}