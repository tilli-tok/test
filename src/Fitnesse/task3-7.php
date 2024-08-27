<?php
require_once "/var/www/html/vendor/autoload.php";

use CleanCode\Fitnesse\SetupTeardownIncluder;

$pageData = 'Text';
$isSuite = true;

$setupTeardown = new SetupTeardownIncluder();
echo $setupTeardown->renderFromPageData($pageData);
echo $setupTeardown->renderFromPageDataSuite($pageData, $isSuite);
