<?php
declare(strict_types=1);
namespace Listing14;

use CleanCode\Listing14\ArgsException;
use CleanCode\Listing14\ErrorCode;
use Exception;
use PHPUnit\Framework\TestCase;

class ArgsExceptionTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testUnexpectedMessage(): void
    {
        $e = new ArgsException(ErrorCode::UNEXPECTED_ARGUMENT, 'x');
        $this->assertEquals("Argument -x unexpected.", $e->errorMessage());
    }

    /**
     * @throws Exception
     */
    public function testMissingStringMessage(): void
    {
        $e = new ArgsException(ErrorCode::MISSING_STRING, 'x');
        $this->assertEquals("Could not find string parameter for -x.", $e->errorMessage());
    }

    /**
     * @throws Exception
     */
    public function testInvalidIntegerMessage(): void
    {
        $e = new ArgsException(ErrorCode::INVALID_INTEGER, 'x', 'Forty two');
        $this->assertEquals("Argument -x expects an integer but was 'Forty two'.", $e->errorMessage());
    }

    /**
     * @throws Exception
     */
    public function testMissingIntegerMessage(): void
    {
        $e = new ArgsException(ErrorCode::MISSING_INTEGER, 'x');
        $this->assertEquals("Could not find integer parameter for -x.", $e->errorMessage());
    }

    /**
     * @throws Exception
     */
    public function testInvalidDoubleMessage(): void
    {
        $e = new ArgsException(ErrorCode::INVALID_DOUBLE, 'x', 'Forty two');
        $this->assertEquals("Argument -x expects a double but was 'Forty two'.", $e->errorMessage());
    }

    /**
     * @throws Exception
     */
    public function testMissingDoubleMessage(): void
    {
        $e = new ArgsException(ErrorCode::MISSING_DOUBLE, 'x');
        $this->assertEquals("Could not find double parameter for -x.", $e->errorMessage());
    }
}
