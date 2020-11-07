<?php

namespace src\models\money;

abstract class CurrencyPrototype
{
    public function __toString(): string
    {
        return $this->getShortName();
    }

    /** Return shor name of currency */
    abstract public function getShortName(): string;

}