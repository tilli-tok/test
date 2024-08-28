<?php
require_once "/var/www/html/vendor/autoload.php";

use CleanCode\Comments\PrimeGenerator;

$maxValue = 40;

$primes = PrimeGenerator::generatePrimes($maxValue);

echo "Простые числа до $maxValue:\n";
print_r($primes);