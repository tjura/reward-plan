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
            $thresholdStart = 0.0;
            $thresholdEnd = 0.0;
            $this->setThresholdAmount($threshold, $thresholdStart, $thresholdEnd, $affiliateBalanceMin, $affiliateBalanceMax);
            $commonPart = $this->getRangesCommonPart([$affiliateBalanceMin, $affiliateBalanceMax, $thresholdStart, $thresholdEnd]);

            if ($revenuePayment->getAmount() < 0) {
                $commonPart *= -1;
            }

            $provisionSum->add($this->getProvisionAmount($commonPart, $threshold->getPercentage()));
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
        $minAmount = $threshold->getMinAmount();
        $maxAmount = $threshold->getMaxAmount();

        if ((null === $minAmount || $affiliateBalanceMax >= $minAmount->getAmount()) && (null === $maxAmount || $affiliateBalanceMin <= $maxAmount->getAmount())) {
            return true;
        }

        return false;
    }

    /**
     * @param Threshold $threshold
     * @param float     $thresholdStart
     * @param float     $thresholdEnd
     * @param float     $affiliateBalanceMin
     * @param float     $affiliateBalanceMax
     */
    private function setThresholdAmount(Threshold $threshold, float &$thresholdStart, float &$thresholdEnd, float $affiliateBalanceMin, float $affiliateBalanceMax): void
    {
        $thresholdMin = $threshold->getMinAmount();
        $thresholdMax = $threshold->getMaxAmount();

        if (null === $thresholdMin) {
            $thresholdStart = $affiliateBalanceMin;
        } else {
            $thresholdStart = $thresholdMin->getAmount();
        }

        if (null === $thresholdMax) {
            $thresholdEnd = $affiliateBalanceMax;
        } else {
            $thresholdEnd = $thresholdMax->getAmount();
        }
    }

    /**
     * @param array $amounts
     *
     * @return float
     */
    protected function getRangesCommonPart(array $amounts): float
    {
        sort($amounts);

        return $amounts[2] - $amounts[1];
    }

    /**
     * @param float $amount
     * @param float $percent
     *
     * @return float
     */
    protected function getProvisionAmount(float $amount, float $percent): float
    {
        return $amount * $percent;
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

    /**
     * @return bool
     */
    abstract protected function isMirrorThresholdsActive(): bool;

}