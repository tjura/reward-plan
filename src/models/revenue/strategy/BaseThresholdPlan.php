<?php

namespace src\models\revenue\strategy;

use src\models\money\Amount;
use src\models\revenue\RevenueStrategy;
use src\models\revenue\WrongValueException;
use src\models\threshold\Threshold;

class BaseThresholdPlan extends RevenueStrategy
{
    /**
     * @throws WrongValueException
     */
    protected function setThresholds(): void
    {
        $this->addThreshold(new Threshold(0.1, new Amount(0.00), new Amount(10000.00)));
        $this->addThreshold(new Threshold(0.2, new Amount(10000.00), new Amount(100000.00)));
    }

    protected function isMirrorThresholdsActive(): bool
    {
        return true;
    }
}