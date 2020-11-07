<?php

namespace src\models\affiliate;

use src\models\player\PlayerCollection;
use src\models\user\UserPrototype;

class Affiliate extends UserPrototype
{
    protected ?PlayerCollection $playersCollection = null;

    public function addPlayers(PlayerCollection $collection)
    {
        $this->playersCollection = $collection;
    }

    public function getPlayersCollection(): PlayerCollection
    {
        if (null === $this->playersCollection) {
            throw new ConfigurationException('You try get players when players is not set');
        }

        return $this->playersCollection;
    }

}