<?php

namespace src\models\player;

class PlayerCollection
{

    /** @var Player[] */
    private array $items = [];

    /**
     * Adding player to Affiliate account
     * Add will do nothing is id was repeated
     *
     * @param Player $player
     *
     * @return bool if adding was successful
     */
    public function addPlayer(Player $player): bool
    {
        if ($this->isPlayerIncluded($player)) {
            return false;
        }

        $id = $player->getId();
        $this->items[$id] = $player;

        return true;
    }

    /**
     * @param Player $player
     *
     * @return bool
     */
    private function isPlayerIncluded(Player $player): bool
    {
        if (isset($this->items[$player->getId()])) {
            return true;
        }

        return false;
    }

    /**
     * @return Player[]
     */
    public function getItems(): array
    {
        return $this->items;
    }
}