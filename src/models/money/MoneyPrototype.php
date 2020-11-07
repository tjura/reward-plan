<?php

namespace src\models\money;

use function sprintf;

/**
 * Base money class.
 * Should contains only money property Amount and Currency and base operations
 *
 * Class MoneyPrototype
 * @package src\models\money
 */
abstract class MoneyPrototype extends AmountPrototype implements MoneyInterface
{
    /** @var CurrencyPrototype */
    protected CurrencyPrototype $currency;

    public function __construct(float $amount, CurrencyPrototype $currencyPrototype)
    {
        $this->currency = $currencyPrototype;
        parent::__construct($amount);
    }

    public function __toString(): string
    {
        return sprintf('%s %s', parent::__toString(), $this->currency);
    }

    public function getCurrency(): CurrencyPrototype
    {
        return $this->currency;
    }

}