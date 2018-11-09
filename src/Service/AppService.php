<?php
/**
 * Created by IntelliJ IDEA.
 * User: george
 * Date: 09.11.18
 * Time: 21:16
 */

namespace App\Service;


use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Yaml\Yaml;

class AppService
{
    /**
     * @var mixed
     */
    private $config;
    /**
     * @var PropertyAccessor
     */
    private $propertyAccessor;

    public function __construct()
    {
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();

        $this->config = Yaml::parseFile('../../config/app-config.yaml');
        var_dump($this->config);
    }

    public function getConfigParameter(string $path) {
        return $this->propertyAccessor->getValue($this->config, $path);
    }
}