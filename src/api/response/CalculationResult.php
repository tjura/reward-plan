<?php

namespace src\api\response;

use src\api\RequestModelPrototype;

class CalculationResult extends RequestModelPrototype
{
    public int $id;

    public float $revenueAmount;

    public float $rewardAmount;

    public string $currency;

    /**
     * CalculationResult constructor.
     *
     * @param int    $id
     * @param float  $revenueAmount
     * @param float  $rewardAmount
     * @param string $currency
     */
    public function __construct(int $id, float $revenueAmount, float $rewardAmount, string $currency)
    {
        $this->id = $id;
        $this->revenueAmount = $revenueAmount;
        $this->rewardAmount = $rewardAmount;
        $this->currency = $currency;
    }

}