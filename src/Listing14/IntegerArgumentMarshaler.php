<?php
namespace CleanCode\Listing14;

use Iterator;
//use OutOfBoundsException;

class IntegerArgumentMarshaler implements ArgumentMarshaler
{
    private int $intValue = 0;

    /**
     * @throws ArgsException
     */
    public function set(Iterator $currentArgument): void
    {
        $parameter = $currentArgument->current();
        try {
            $this->ensureValidArgument($currentArgument);
            $this->validateNumericParameter($parameter);

            $this->intValue = (int)$parameter;
            $currentArgument->next();
        //} catch (OutOfBoundsException) {
        //    throw new ArgsException(ErrorCode::MISSING_INTEGER, null);
        } catch (NumberFormatException) {
            throw new ArgsException(ErrorCode::INVALID_INTEGER, null, $parameter);
        }
    }
    public function get(): int
    {
        return $this->intValue;
    }

    /**
     * @throws ArgsException
     */
    private function ensureValidArgument(Iterator $currentArgument): void
    {
        if (!$currentArgument->valid()) {
            throw new ArgsException(ErrorCode::MISSING_INTEGER, null);
        }
    }

    /**
     * @throws NumberFormatException
     */
    private function validateNumericParameter(string $parameter): void
    {
        if (!is_numeric($parameter)) {
            throw new NumberFormatException("Invalid numeric format: $parameter");
        }
    }
}