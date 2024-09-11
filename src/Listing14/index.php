<?php
require_once "/var/www/html/vendor/autoload.php";

use CleanCode\Listing14\Args;
use CleanCode\Listing14\ArgsException;

try {
    $args = new Args('l,p#,d*,x#', ['-lpd', 'true', '42', 'param', '16']);

    var_dump($args->getBoolean('l'));
    var_dump($args->getInt('p'));
    var_dump($args->getString('d'));
    var_dump($args->getInt('x'));
} catch (ArgsException $e) {
    var_dump($e->getMessage());
} catch (Exception $e) {
}