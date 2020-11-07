<?php

namespace src\models\revenue;

use src\models\money\Money;
use src\models\money\MoneyInterface;
use src\models\threshold\Threshold;

use function max;
use function min;
use function sort;

abstract class RevenueStrategy
{
    /** @var Threshold[] */
    private array $thresholds = [];

    public function __construct()
    {
        $this->setThresholds();
    }

    abstract protected function setThresholds(): void;

    /**
     * @param MoneyInterface $startingAccountBalance
     * @param MoneyInterface $revenuePayment
     *
     * @return MoneyInterface
     * @todo Support multiple currencies (but now its not required in specification)
     */
    public function calculate(MoneyInterface $startingAccountBalance, MoneyInterface $revenuePayment): MoneyInterface
    {
        $startingAccountAmount = $startingAccountBalance->getAmount();
        $revenueAmount = $revenuePayment->getAmount();
        $endingMoneyAmount = $startingAccountAmount + $revenueAmount;
        $provisionSum = new Money(0.0, $revenuePayment->getCurrency());
        foreach ($this->thresholds as $threshold) {
            $affiliateBalanceMin = min($startingAccountAmount, $endingMoneyAmount);
            $affiliateBalanceMax = max($startingAccountAmount, $endingMoneyAmount);

            if (false === $this->isMoneyRangeMatchToThreshold($affiliateBalanceMin, $affiliateBalanceMax, $threshold)) {
                continue;
            }

            $thresholdStart = $threshold->getMinAmount()->getAmount();
            $thresholdEnd = $threshold->getMaxAmount()->getAmount();

            $values = [$affiliateBalanceMin, $affiliateBalanceMax, $thresholdStart, $thresholdEnd];
            sort($values);
            $commonPart = $values[2] - $values[1];
            $provisionSum->add($commonPart * $threshold->getPercentage());
        }

        return $provisionSum;
    }

    /**
     * @param float     $affiliateBalanceMin
     * @param float     $affiliateBalanceMax
     * @param Threshold $threshold
     *
     * @return bool
     */
    protected function isMoneyRangeMatchToThreshold(float $affiliateBalanceMin, float $affiliateBalanceMax, Threshold $threshold): bool
    {
        if ($affiliateBalanceMax > $threshold->getMinAmount()->getAmount() && $affiliateBalanceMin < $threshold->getMinAmount()->getAmount()) {
            return true;
        }

        return false;
    }

    /**
     * @param Threshold $threshold
     */
    protected function addThreshold(Threshold $threshold)
    {
        if ($this->isMirrorThresholdsActive()) {
            $this->thresholds[] = $threshold->getMirrored();
        }
        $this->thresholds[] = $threshold;
    }

    abstract protected function isMirrorThresholdsActive(): bool;

}