<?php

namespace src\controllers;

use src\api\request\Player;
use src\api\response\CalculationResult;
use src\models\affiliate\Affiliate;
use src\models\money\MoneyInterface;
use src\models\player\Player as PlayerModel;
use src\models\player\PlayerCollection;
use src\models\revenue\strategy\BaseRevenuePlan;
use src\models\user\UserInterface;
use src\services\CalculationService;

use function json_encode;

/**
 * Simplified controller to handle request
 * @package src\controllers
 *
 */
class ApiController
{
    /**
     * @return string
     * @throws \ReflectionException
     */
    public function calculations(): string
    {
        /** @var Player[] $playersRequest */
        $playersRequest = Player::populate($_POST);

        $playerCollection = new PlayerCollection();

        foreach ($playersRequest as $playerRequest) {
            $playerCollection->addPlayer(new PlayerModel($playerRequest->id, $playerRequest->amount));
        }

        $affiliate = new Affiliate(6);
        $affiliate->addPlayers($playerCollection);

        $provisionsCollected = CalculationService::calculate($affiliate, new BaseRevenuePlan());
        /** @var CalculationResult[] $data */
        $data = [];
        foreach ($provisionsCollected as $item) {
            /** @var UserInterface $player */
            $player = $item[0];
            /** @var MoneyInterface $provision */
            $provision = $item[1];
            $data[] = new CalculationResult($player->getId(), $player->getAccountBalance()->getAmount(), $provision->getAmount(), $player->getAccountBalance()->getCurrency());
        }

        return json_encode([
            'status' => 200,
            'response' => $data,
        ]);
    }
}