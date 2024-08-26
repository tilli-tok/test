<?php
require_once "/var/www/html/vendor/autoload.php";

use CleanCode\ChapterFunctions\SetupTeardownIncluder;

$pageData = '';
$isSuite = '';

$setupTeardown = new SetupTeardownIncluder();
echo $setupTeardown->render($pageData, $isSuite);