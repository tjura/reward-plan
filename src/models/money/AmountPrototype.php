<?php

namespace src\models\money;

use function abs;
use function round;
use function sprintf;

/**
 * Base money class.
 * Should contains only money property Amount and Currency and base operations
 *
 * Class MoneyPrototype
 * @package src\models\money
 */
abstract class AmountPrototype implements MoneyOperationsInterface, AmountInterface
{
    public const ROUND_PRECISION = 2;

    /** @var float Current amount of money */
    protected float $amount;

    public function __construct(float $amount)
    {
        $this->amount = $amount;
    }

    public function __toString(): string
    {
        return sprintf('%s', $this->amount);
    }

    public function add(float $amount): self
    {
        $this->amount += $amount;

        return $this;
    }

    public function subtract(float $amount): self
    {
        $this->amount -= $amount;

        return $this;
    }

    public function abs(): self
    {
        $this->amount = abs($this->amount);

        return $this;
    }

    public function getAmount(): float
    {
        return round($this->amount, self::ROUND_PRECISION);
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function isPositive(): bool
    {
        return $this->amount >= 0;
    }

    public function isNegative(): bool
    {
        return $this->amount < 0;
    }

    public function reverse(): self
    {
        $this->amount *= -1;

        return $this;
    }

}