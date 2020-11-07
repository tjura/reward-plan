<?php

namespace src\models\user;

use src\models\money\MoneyPrototype;

interface UserInterface
{
    public function getId(): int;

    public function getAccountBalance(): MoneyPrototype;
}