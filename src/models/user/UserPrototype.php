<?php

namespace src\models\user;

use src\models\money\currency\Euro;
use src\models\money\Money;
use src\models\money\MoneyPrototype;

abstract class UserPrototype implements UserInterface
{
    /** @var int Player ID */
    private int $id;

    /** @var MoneyPrototype Wont support multiple currency accounts */
    private MoneyPrototype $accountBalance;

    public function __construct(int $id)
    {
        $this->id = $id;
        $this->accountBalance = new Money(0.0, new Euro());
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAccountBalance(): MoneyPrototype
    {
        return $this->accountBalance;
    }

}