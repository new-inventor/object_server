<?php


namespace App\Entity;


class AbstractEntity
{
    public function load(array $params)
    {
        foreach ($params as $key => $value) {
            $funcName = 'set' . strtoupper($key[0]) . substr($key, 1);
            $this->$funcName($value);
        }
    }
}