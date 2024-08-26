<?php
require_once "/var/www/html/vendor/autoload.php";

use CleanCode\ChapterFunctions\SetupTeardownIncluder;

$pageData = '<html><body>Hello, world</body></html>';
$isSuite = true;

$setupTeardown = new SetupTeardownIncluder();
echo $setupTeardown->renderWithoutSuite($pageData);
echo $setupTeardown->render($pageData, $isSuite);
