<?php


namespace App\Api\WebServer\Response;


use App\Api\Error\ApiError;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

class GetTypesResponse extends AbstractResponse
{
    private $sensorTypes;
    private $actuatorTypes;
    private $eventTypes;
    private $triggers;
    private $controllerTypes;

    /**
     * @return mixed
     */
    public function getSensorTypes()
    {
        return $this->sensorTypes;
    }

    /**
     * @return mixed
     */
    public function getActuatorTypes()
    {
        return $this->actuatorTypes;
    }

    /**
     * @return mixed
     */
    public function getEventTypes()
    {
        return $this->eventTypes;
    }

    /**
     * @return mixed
     */
    public function getTriggers()
    {
        return $this->triggers;
    }

    /**
     * @return mixed
     */
    public function getControllerTypes()
    {
        return $this->controllerTypes;
    }

    /**
     * @param array | null $response
     */
    protected function mapResponse($response): void
    {
        if (array_key_exists('sensor_type', $response['tables'])) {
            $this->sensorTypes = $this->mapSensorTypes($response['tables']['sensor_type']);
        }
        if (array_key_exists('actuator_type', $response['tables'])) {
            $this->actuatorTypes = $this->mapActuatorTypes($response['tables']['actuator_type']);
        }
        if (array_key_exists('event_type', $response['tables'])) {
            $this->eventTypes = $this->mapEventTypes($response['tables']['event_type']);
        }
        if (array_key_exists('trigger', $response['tables'])) {
            $this->triggers = $this->mapTriggers($response['tables']['trigger']);
        }
        if (array_key_exists('controller_model', $response['tables'])) {
            $this->controllerTypes = $this->mapControllerTypes($response['tables']['controller_model']);
        }
    }

    private function mapSensorTypes($sensorTypes)
    {
        return array_map(
            function ($sensorType) {
                return [
                    'id' => $sensorType['sensor_type_id'],
                    'title' => $sensorType['title'] ?? '',
                ];
            },
            $sensorTypes
        );
    }

    private function mapActuatorTypes($actuatorTypes)
    {
        return array_map(
            function ($actuatorType) {
                return [
                    'id' => $actuatorType['actuator_type_id'],
                    'title' => $actuatorType['title'] ?? '',
                ];
            },
            $actuatorTypes
        );
    }

    private function mapEventTypes($eventTypes)
    {
        return array_map(
            function ($eventType) {
                return [
                    'id' => $eventType['event_type_id'],
                    'title' => $eventType['title'] ?? '',
                ];
            },
            $eventTypes
        );
    }

    private function mapTriggers($triggers)
    {
        return array_map(
            function ($trigger) {
                return [
                    'id' => $trigger['trigger_id'],
                    'status' => $trigger['status'] ?? '',
                    'content' => $trigger['content'] ?? '',
                ];
            },
            $triggers
        );
    }

    private function mapControllerTypes($controllerModels)
    {
        return array_map(
            function ($controllerModel) {
                return [
                    'id' => $controllerModel['controller_model_id'],
                    'title' => $controllerModel['title'] ?? '',
                ];
            },
            $controllerModels
        );
    }

    protected function parseResponse(PsrResponseInterface $response): array
    {
        $res = parent::parseResponse($response);
        if (!array_key_exists('tables', $res)) {
            throw new ApiError("No 'tables' parameter found in response");
        }
        return $res;
    }

}