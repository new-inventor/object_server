<?php


namespace App\Api\WebServer\Request;


class SensorValueForEventRequest extends AbstractRequest
{
    protected $uri = '/events/pushEvent';

    /**
     * @var int
     */
    public $sensorId;
    /**
     * @var int
     */
    public $value;
    /**
     * @var int
     */
    public $level;

    /**
     * SensorValueForEventRequest constructor.
     * @param int $sensorId
     * @param int $value
     * @param int $level
     */
    public function __construct(int $sensorId, int $value, int $level)
    {
        $this->sensorId = $sensorId;
        $this->value = $value;
        $this->level = $level;
    }

    public function toPostArray(): array
    {
        return [
            'sensor_id' => $this->sensorId,
            'value' => $this->value,
            'level' => $this->level,
        ];
    }
}