<?php

namespace src\api\request;

use src\api\RequestModelPrototype;

class Player extends RequestModelPrototype
{
    public int $id;

    public float $amount;

    public string $currency;
}