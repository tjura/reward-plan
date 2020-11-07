<?php

namespace src\api\exception;

use Throwable;

class RequestException extends ApiException
{

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        if (empty($message)) {
            $message = 'Bad request';
        }

        if (0 === $code) {
            $code = 400;
        }

        parent::__construct($message, $code, $previous);
    }

}