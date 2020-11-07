<?php

use src\models\affiliate\Affiliate;
use src\models\affiliate\ConfigurationException;
use src\models\money\Amount;
use src\models\money\currency\Euro;
use src\models\money\Money;
use src\models\money\MoneyInterface;
use src\models\player\Player;
use src\models\player\PlayerCollection;
use src\models\revenue\RevenueStrategy;
use src\models\revenue\strategy\BaseThresholdPlan;
use src\models\threshold\Threshold;
use src\services\CalculationService;

class CalculationResultTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    public function testBasicCalculationResult()
    {
        /*
         * Setting player and affiliate
         */
        $affiliate = new Affiliate(1);
        $playerCollection = new PlayerCollection();
        $playerCollection->addPlayer(new Player(1, 1000.00));
        $affiliate->addPlayers($playerCollection);
        /*
         * Get Threshold plan
         */
        $thresholdPlan = new BaseThresholdPlan();
        /*
         * Do calculations
         */
        $calculationResult = CalculationService::calculate($affiliate, $thresholdPlan);
        /*
         * Check calculation result
         */
        /** @var MoneyInterface $provision */
        $provision = $calculationResult[0][1];
        $this->assertEquals(100.00, $provision->getAmount());
    }

    public function testExpectConfigurationException()
    {
        $this->expectException(ConfigurationException::class);
        $affiliate = new Affiliate(1);
        CalculationService::calculate($affiliate, new BaseThresholdPlan());
    }

    public function testExampleDataSetOne()
    {
        $this->checkCalculations([
            [9500, 950],
            [2340.6, 418.12],
            [-3256.12, -509.67],
            [-120000, -19858.45],
            [231415.52, 38000],
        ]);
    }

    // tests

    /**
     * @param array $operations
     */
    private function checkCalculations(array $operations)
    {
        $thresholdPlan = new BaseThresholdPlan();
        $startBalance = new Money(0.0, new Euro());

        foreach ($operations as $operation) {
            $revenue = new Money($operation[0], new Euro());
            $expectation = $operation[1];
            $result = $thresholdPlan->calculate($startBalance, $revenue);
            $startBalance->add($revenue->getAmount());
            $this->assertEquals($expectation, $result->getAmount(), 'Calculation wrong');
        }
    }

    public function testExampleDataSetTwo()
    {
        $this->checkCalculations([
            [9000, 900],
            [2000, 300],
            [-3000, -400],
        ]);
    }

    /**
     * @throws Exception
     */
    public function testInfiniteRange()
    {
        $incomeAmount = 1000000;

        /** @var RevenueStrategy $thresholdPlan */
        $thresholdPlan = $this->construct(RevenueStrategy::class, [], [
            'setThresholds' => function (){
            },
            'isMirrorThresholdsActive' => function (){
                return true;
            },
            'thresholds' => [
                new Threshold(0.1, new Amount(0.00), new Amount(10000.00)),
                new Threshold(0.2, new Amount(10000.00), null),
            ],
        ]);

        $startBalance = new Money(0.0, new Euro());
        $revenue = new Money($incomeAmount, new Euro());

        $result = $thresholdPlan->calculate($startBalance, $revenue);

        $firstProvision = 10000 * 0.1;
        $secondProvision = ($incomeAmount - 10000) * 0.2;

        $this->assertEquals($firstProvision + $secondProvision, $result->getAmount());
    }

    protected function _before()
    {
    }

    protected function _after()
    {
    }

}