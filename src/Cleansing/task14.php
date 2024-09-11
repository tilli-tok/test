<?php
require_once "/var/www/html/vendor/autoload.php";

use CleanCode\Cleansing\Args;
use CleanCode\Cleansing\ArgsException;

$schema = '';
try {
    $args = new Args($schema, $argv);
} catch (ArgsException $e) {
    echo "Error: " . $e->errorMessage(). PHP_EOL;
}