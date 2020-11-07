<?php

namespace src\models\money\currency;

use src\models\money\CurrencyPrototype;

class Euro extends CurrencyPrototype
{
    public function getShortName(): string
    {
        return 'eur';
    }
}