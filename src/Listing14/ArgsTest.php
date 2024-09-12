<?php
declare(strict_types=1);
namespace CleanCode\Listing14;

use Exception;
use PHPUnit\Framework\TestCase;

class ArgsTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testCreateWithNoSchemaOrArguments(): void
    {
        $args = new Args('', []);
        $this->assertEquals(0, $args->cardinality());
    }

    public function testWithNoSchemaButWithOneArgument(): void
    {
        try {
            new Args('', ['-x']);
            $this->fail();
        } catch (ArgsException $e) {
            $this->assertEquals(ErrorCode::UNEXPECTED_ARGUMENT, $e->getErrorCode());
            $this->assertEquals('x', $e->getErrorArgumentId());
        }
    }

    public function testWithNoSchemaButWithMultipleArguments(): void
    {
        try {
            new Args('', ['-x', '-y']);
            $this->fail();
        } catch (ArgsException $e) {
            $this->assertEquals(ErrorCode::UNEXPECTED_ARGUMENT, $e->getErrorCode());
            $this->assertEquals('x', $e->getErrorArgumentId());
        }
    }

    public function testNonLetterSchema(): void
    {
        try {
            new Args('*', []);
            $this->fail('Args constructor should have thrown exception');
        } catch (ArgsException $e) {
            $this->assertEquals(ErrorCode::INVALID_ARGUMENT_NAME, $e->getErrorCode());
            $this->assertEquals('*', $e->getErrorArgumentId());
        }
    }

    public function testInvalidArgumentFormat(): void
    {
        try {
            new Args('f~', []);
            $this->fail('Args constructor should have thrown exception');
        } catch (ArgsException $e) {
            $this->assertEquals(ErrorCode::INVALID_FORMAT, $e->getErrorCode());
            $this->assertEquals('f', $e->getErrorArgumentId());
        }
    }

    /**
     * @throws ArgsException
     */
    public function testSimpleBooleanPresent(): void
    {
        $args = new Args('x', ['-x']);
        $this->assertEquals(1, $args->cardinality());
        $this->assertTrue($args->getBoolean('x'));
    }

    /**
     * @throws ArgsException
     */
    public function testSimpleStringPresent(): void
    {
        $args = new Args('x*', ['-x', 'param']);
        $this->assertEquals(1, $args->cardinality());
        $this->assertTrue($args->has('x'));
        $this->assertEquals('param', $args->getString('x'));
    }

    public function testMissingStringArgument(): void
    {
        try {
            new Args('x*', ['-x']);
            $this->fail();
        } catch (ArgsException $e) {
            $this->assertEquals(ErrorCode::MISSING_STRING, $e->getErrorCode());
            $this->assertEquals('x', $e->getErrorArgumentId());
        }
    }

    /**
     * @throws ArgsException
     */
    public function testSpacesInFormat(): void
    {
        $args = new Args('x, y', ['-xy']);
        $this->assertEquals(2, $args->cardinality());
        $this->assertTrue($args->has('x'));
        $this->assertTrue($args->has('y'));
    }

    /**
     * @throws ArgsException
     */
    public function testSimpleIntPresent(): void
    {
        $args = new Args('x#', ['-x', '42']);
        $this->assertEquals(1, $args->cardinality());
        $this->assertTrue($args->has('x'));
        $this->assertEquals(42, $args->getInt('x'));
    }

    public function testInvalidInteger(): void
    {
        try {
            new Args('x#', ['-x', 'Forty two']);
            $this->fail();
        } catch (ArgsException $e) {
            $this->assertEquals(ErrorCode::INVALID_INTEGER, $e->getErrorCode());
            $this->assertEquals('x', $e->getErrorArgumentId());
            $this->assertEquals('Forty two', $e->getErrorParameter());
        }
    }

    public function testMissingInteger(): void
    {
        try {
            new Args('x#', ['-x']);
            $this->fail();
        } catch (ArgsException $e) {
            $this->assertEquals(ErrorCode::MISSING_INTEGER, $e->getErrorCode());
            $this->assertEquals('x', $e->getErrorArgumentId());
        }
    }

    /**
     * @throws ArgsException
     */
    public function testSimpleDoublePresent(): void
    {
        $args = new Args('x##', ['-x', '42.3']);
        $this->assertEquals(1, $args->cardinality());
        $this->assertTrue($args->has('x'));
        $this->assertEquals(42.3, $args->getDouble('x'), (string).001);
    }

    public function testInvalidDouble(): void
    {
        try {
            new Args('x##', ['-x', 'Forty two']);
            $this->fail();
        } catch (ArgsException $e) {
            $this->assertEquals(ErrorCode::INVALID_DOUBLE, $e->getErrorCode());
            $this->assertEquals('x', $e->getErrorArgumentId());
            $this->assertEquals('Forty two', $e->getErrorParameter());
        }
    }

    public function testMissingDouble(): void
    {
        try {
            new Args('x##', ['-x']);
            $this->fail();
        } catch (ArgsException $e) {
            $this->assertEquals(ErrorCode::MISSING_DOUBLE, $e->getErrorCode());
            $this->assertEquals('x', $e->getErrorArgumentId());
        }
    }
}