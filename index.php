<?php

use src\services\CalculationService;

require_once 'bootstrap.php';

error_reporting(E_ERROR | E_WARNING | E_PARSE);

CalculationService::run();