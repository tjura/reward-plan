<?php

namespace src\models\player;

use src\models\user\UserPrototype;

class Player extends UserPrototype
{
    public function __construct(int $id, float $accountBalance)
    {
        parent::__construct($id);
        $this->getAccountBalance()->setAmount($accountBalance);
    }
}