<?php

namespace src\api\exception;

use JsonSerializable;
use LogicException;

class ApiException extends LogicException implements JsonSerializable
{

    public function jsonSerialize()
    {
        return ['status' => $this->getCode(), 'message' => $this->getMessage()];
    }
}