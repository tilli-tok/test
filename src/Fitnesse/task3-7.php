<?php
require_once "/var/www/html/vendor/autoload.php";

use CleanCode\Fitnesse\SetupTeardownIncluder;
use CleanCode\Fitnesse\PageData;

$pageData = new PageData();
$pageData->setContent("\nПроверочный текст ---- Тест\n");
$isSuite = true;

try {
    echo SetupTeardownIncluder::renderFromPageData($pageData);
} catch (Exception $e) {
}
try {
    echo SetupTeardownIncluder::renderFromPageDataSuite($pageData, $isSuite);
} catch (Exception $e) {
}