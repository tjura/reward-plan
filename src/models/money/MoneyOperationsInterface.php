<?php

namespace src\models\money;

interface MoneyOperationsInterface
{
    public function add(float $amount): self;

    public function subtract(float $amount): self;

    public function abs(): self;

    public function isPositive(): bool;

    public function isNegative(): bool;

    public function reverse(): self;
}