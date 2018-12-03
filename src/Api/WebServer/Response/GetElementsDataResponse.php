<?php


namespace App\Api\WebServer\Response;


class GetElementsDataResponse extends AbstractResponse
{
    private $rooms;
    private $element_types;
    private $elements;
    private $element_actuators;
    private $element_sensors;
    private $event_types;


    protected function mapResponse($response): void
    {
        if (array_key_exists('room', $response)) {
            $this->rooms = $response['room'];
        }
        if (array_key_exists('element_type', $response)) {
            $this->element_types = $response['element_type'];
        }
        if (array_key_exists('event_type', $response)) {
            $this->event_types = $response['event_type'];
        }
        if (array_key_exists('element', $response)) {
            $this->elements = $response['element'];
        }
        if (array_key_exists('element_actuator', $response)) {
            $this->element_actuators = $response['element_actuator'];
        }
        if (array_key_exists('element_sensor', $response)) {
            $this->element_sensors = $response['element_sensor'];
        }
    }

    /**
     * @return mixed
     */
    public function getRooms()
    {
        return $this->rooms;
    }

    /**
     * @return mixed
     */
    public function getElementTypes()
    {
        return $this->element_types;
    }

    /**
     * @return mixed
     */
    public function getElements()
    {
        return $this->elements;
    }

    /**
     * @return mixed
     */
    public function getElementActuators()
    {
        return $this->element_actuators;
    }

    /**
     * @return mixed
     */
    public function getElementSensors()
    {
        return $this->element_sensors;
    }

    /**
     * @return mixed
     */
    public function getEventTypes()
    {
        return $this->event_types;
    }

}