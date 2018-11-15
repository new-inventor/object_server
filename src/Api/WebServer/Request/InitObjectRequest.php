<?php


namespace App\Api\WebServer\Request;


class InitObjectRequest extends AbstractRequest
{
    /**
     * @var string
     */
    public $objectTitle;
    /**
     * @var string
     */
    public $objectAddress;
    protected $uri = '/state/initObject';
    /**
     * @var bool
     */
    protected $useAccountToken = true;

    /**
     * InitObjectRequest constructor.
     * @param string $objectTitle
     * @param string $objectAddress
     */
    public function __construct(string $objectTitle, string $objectAddress)
    {
        $this->objectTitle = $objectTitle;
        $this->objectAddress = $objectAddress;
    }

    public function toPostArray(): array
    {
        return [
            'object_title' => $this->objectTitle,
            'object_adress' => $this->objectAddress,
        ];
    }

}