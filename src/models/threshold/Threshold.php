<?php

namespace src\models\threshold;

use src\models\money\Amount;

class Threshold
{
    protected ?Amount $minAmount;

    protected ?Amount $maxAmount;

    protected ?float $percentage;

    /**
     * Threshold constructor.
     *
     * @param float       $percentage 1 = 100%
     * @param Amount|null $minAmount
     * @param Amount|null $maxAmount
     */
    public function __construct(float $percentage, Amount $minAmount = null, Amount $maxAmount = null)
    {
        $this->minAmount = $minAmount;
        $this->maxAmount = $maxAmount;
        $this->percentage = $percentage;
    }

    /**
     * @return Amount|null
     */
    public function getMinAmount(): ?Amount
    {
        return $this->minAmount;
    }

    /**
     * @return Amount|null
     */
    public function getMaxAmount(): ?Amount
    {
        return $this->maxAmount;
    }

    public function getMirrored()
    {
        $threshold = new self($this->getPercentage(), clone $this->minAmount, clone $this->maxAmount);

        return $threshold->mirror();
    }

    /**
     * @return float|null
     */
    public function getPercentage(): ?float
    {
        return $this->percentage;
    }

    /**
     * @return $this
     */
    protected function mirror(): self
    {
        $maxAmount = $this->maxAmount;
        $minAmount = $this->minAmount;

        if ($minAmount) {
            $minAmount->reverse();
        }

        if ($maxAmount) {
            $maxAmount->reverse();
        }

        $this->minAmount = $maxAmount;
        $this->maxAmount = $minAmount;

        return $this;
    }

}