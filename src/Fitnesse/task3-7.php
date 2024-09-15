<?php
require_once "/var/www/html/vendor/autoload.php";

use CleanCode\Fitnesse\SetupTeardownIncluder;
use CleanCode\Fitnesse\PageData;

$pageData = new PageData();
$pageData->setContent("\nПроверочный текст\n");
$isSuite = true;

echo SetupTeardownIncluder::renderFromPageData($pageData);
echo SetupTeardownIncluder::renderFromPageDataSuite($pageData, $isSuite);