<?php

require_once "/var/www/html/vendor/autoload.php";

use CleanCode\Listing14\Args;

use CleanCode\Listing14\TestMissingDouble;
/**
function testSimpleDoublePresent(): void {
    $args = new Args("x##", ["-x", "42.3"]);

    $this->assertTrue($args->isValid());
    $this->assertEquals(1, $args->cardinality());
    $this->assertTrue($args->has('x'));
    $this->assertEquals(42.3, $args->getDouble('x'), 0.001);
}
testSimpleDoublePresent();


public function testInvalidDouble(): void
{
    $args = new Args("x##", ['-x', 'Forty two']);

    $this->assertFalse($args->isValid());
    $this->assertEquals(0, $args->cardinality());
    $this->assertFalse($args->has('x'));
    $this->assertEquals(0, $args->getInt('x'));
    $this->assertEquals(
        "Argument -x expects a double but was 'Forty two'.",
        $args->errorMessage()
    );
}
testInvalidDouble();*/

TestMissingDouble::testMissingDouble();