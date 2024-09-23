<?php
require_once "/var/www/html/vendor/autoload.php";

use CleanCode\Cleansing\Args;
use CleanCode\Cleansing\ArgsException;

$schema = 'x#';
$argv = ['-x', 'Forty two'];

try {
    new Args($schema, $argv);
    $this->fail();
} catch (ArgsException $e) {
    echo "Error: " . $e->errorMessage(). PHP_EOL;
}