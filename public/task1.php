<?php
require_once __DIR__ . '/../vendor/autoload.php';

use MeaningfulNames\GuessStatisticsMessage;

$candidate = 'people';
$count = 1;

$guessStatistics = new GuessStatisticsMessage();
echo $guessStatistics->make($candidate,$count);