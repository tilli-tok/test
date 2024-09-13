<?php
namespace CleanCode\Listing14;

use Exception;
use Iterator;

class IntegerArgumentMarshaler implements ArgumentMarshaler
{
    private int $intValue = 0;

    /**
     * @throws ArgsException
     */
    public function set(Iterator $currentArgument): void
    {
        try {
            $parameter = $currentArgument->current();
            $this->intValue = (int)$parameter;
            $currentArgument->next();
        } catch (Exception) {
            if (empty($parameter)) {
                throw new ArgsException(ErrorCode::MISSING_INTEGER, null);
            } else {
                throw new ArgsException(ErrorCode::INVALID_INTEGER, $parameter);
            }
        }
    }
    public function get(): int
    {
        return $this->intValue;
    }
}