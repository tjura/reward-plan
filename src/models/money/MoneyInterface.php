<?php

namespace src\models\money;

interface MoneyInterface extends AmountInterface
{
    public function getCurrency(): CurrencyPrototype;
}