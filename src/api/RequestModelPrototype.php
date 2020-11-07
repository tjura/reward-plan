<?php

namespace src\api;

use JsonSerializable;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
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

    public static function getName(): string
    {
        $path = explode('\\', get_called_class());

        return strtolower(array_pop($path));
    }

    /**
     * @param array $data mixed
     *
     * @throws ReflectionException
     * @throws RequestException
     */
    protected function setParams(array $data): void
    {
        $reflection = new ReflectionClass($this);
        foreach ($reflection->getProperties() as $property) {
            $propertyName = $property->getName();
            try {
                $this->$propertyName = $this->getPropertyValue($data, $property);
            } catch (TypeError $exception) {
                throw new RequestException(sprintf('Bad request: %s has wrong value. Should be %s given %s', $propertyName, $property->getType(), gettype($value)));
            }
        }
    }

    /**
     * @param array              $data
     * @param ReflectionProperty $property
     *
     * @return mixed
     */
    private function getPropertyValue(array $data, ReflectionProperty $property)
    {
        $name = $property->getName();
        if (isset($data[$name])) {
            return $data[$name];
        }

        return null;
    }

    public function jsonSerialize()
    {
        return (object)get_object_vars($this);
    }

}