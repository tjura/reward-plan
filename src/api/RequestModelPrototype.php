<?php

namespace src\api;

use JsonSerializable;
use ReflectionClass;
use ReflectionException;
use src\api\exception\RequestException;
use TypeError;

use function array_pop;
use function get_called_class;
use function get_object_vars;
use function gettype;
use function is_array;
use function sprintf;
use function strtolower;

abstract class RequestModelPrototype implements JsonSerializable
{
    /**
     * @param array $rawRequest
     *
     * @return self[]
     * @throws ReflectionException
     */
    public static function populate(array $rawRequest): array
    {
        $requestData = $rawRequest[self::getName()];

        if (empty($requestData)) {
            return [];
        }

        $result = [];

        if (is_array($requestData)) {
            $calledClass = get_called_class();
            /** @var RequestModelPrototype $model */
            foreach ($requestData as $vars) {
                $model = new $calledClass();
                $model->setParams($vars);
                $result[] = $model;
            }
        }

        return $result;
    }

    protected static function getName(): string
    {
        $path = explode('\\', get_called_class());

        return strtolower(array_pop($path));
    }

    /**
     * @param array $vars
     *
     * @throws ReflectionException
     */
    protected function setParams(array $vars): void
    {
        $reflection = new ReflectionClass($this);
        foreach ($reflection->getProperties() as $property) {
            $name = $property->getName();
            if (isset($vars[$name])) {
                $value = $vars[$name];
            } else {
                $value = null;
            }
            try {
                if (empty($value)) {
                    $this->$name = null;
                } else {
                    $this->$name = $value;
                }
            } catch (TypeError $exception) {
                throw new RequestException(sprintf('Bad request: %s has wrong value. Should be %s given %s', $name, $property->getType(), gettype($value)));
            }
        }
    }

    public function jsonSerialize()
    {
        return (object)get_object_vars($this);
    }

}