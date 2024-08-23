<?php
require_once "/var/www/html/vendor/autoload.php";

use CleanCode\MeaningfulNames\GuessStatisticsMessage;

$candidate = 'people';
$count = 1;

$guessStatistics = new GuessStatisticsMessage();
echo $guessStatistics->make($candidate, $count);