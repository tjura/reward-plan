<?php

namespace src\services;

use Exception;
use src\api\exception\ApiException;
use src\controllers\ApiController;
use src\models\affiliate\Affiliate;
use src\models\revenue\RevenueStrategy;

use function json_encode;

/**
 * Class CalculationService
 * @package src\services
 * Application runner
 */
class CalculationService
{
    /**
     * @todo implement routing
     */
    public static function run()
    {
        header('Content-Type: application/json');
        try {
            $api = new ApiController();
            echo $api->calculations();
        } catch (ApiException $exception) {
            echo json_encode($exception);
        } catch (Exception $exception) {
            echo json_encode(['status' => 500, 'Server error: ' . $exception->getMessage()]);
        }
    }

    /**
     * Calculating operations for prepared affiliate
     *
     * @param Affiliate       $affiliate
     * @param RevenueStrategy $revenueStrategy
     *
     * @return array of [0 => [PlayerPrototype $playerRevenue, MoneyInterface $provision], 1 => ...]
     */
    public static function calculate(Affiliate $affiliate, RevenueStrategy $revenueStrategy): array
    {
        $playersCollection = $affiliate->getPlayersCollection();
        $accountBalance = $affiliate->getAccountBalance();
        $provisionsCollected = [];

        foreach ($playersCollection->getItems() as $player) {
            $playerRevenue = $player->getAccountBalance();
            $provision = $revenueStrategy->calculate($accountBalance, $playerRevenue);
            $provisionsCollected[] = [$player, $provision];
            $affiliate->getAccountBalance()->add($playerRevenue->getAmount());
        }

        return $provisionsCollected;
    }

}