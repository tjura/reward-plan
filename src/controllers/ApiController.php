<?php

namespace src\controllers;

use src\api\exception\RequestException;
use src\api\request\Player;
use src\api\response\CalculationResult;
use src\models\affiliate\Affiliate;
use src\models\money\MoneyInterface;
use src\models\player\Player as PlayerModel;
use src\models\player\PlayerCollection;
use src\models\revenue\strategy\BaseThresholdPlan;
use src\models\user\UserInterface;
use src\services\CalculationService;

use function count;
use function json_encode;

/**
 * Simplified controller to handle request
 * @package src\controllers
 */
class ApiController
{
    /**
     * You can easily implement new ThresholdPlan here
     * @return string
     * @throws \ReflectionException
     */
    public function calculations(): string
    {
        $affiliate = new Affiliate(6);
        $affiliate->addPlayers($this->getPlayersCollection());

        $calculationResults = CalculationService::calculate($affiliate, new BaseThresholdPlan());
        /** @var CalculationResult[] $data */
        $data = [];
        foreach ($calculationResults as $calculationResult) {
            /** @var UserInterface $player */
            $player = $calculationResult[0];
            /** @var MoneyInterface $provision */
            $provision = $calculationResult[1];
            $data[][Player::getName()] = new CalculationResult($player->getId(), $player->getAccountBalance()->getAmount(), $provision->getAmount(), $player->getAccountBalance()->getCurrency());
        }

        return json_encode([
            'status' => 200,
            'response' => $data,
        ]);
    }

    /**
     * @return PlayerCollection
     * @throws \ReflectionException
     */
    private function getPlayersCollection(): PlayerCollection
    {
        $playersRequest = Player::populate($_POST);
        $playerCollection = new PlayerCollection();

        if (0 == count($playersRequest)) {
            throw new RequestException('You need send at least 1 player data');
        }

        foreach ($playersRequest as $playerRequest) {
            $playerCollection->addPlayer(new PlayerModel($playerRequest->id, $playerRequest->amount));
        }

        return $playerCollection;
    }

}