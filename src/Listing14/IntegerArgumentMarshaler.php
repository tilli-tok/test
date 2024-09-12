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
        $parameter = null;
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

    /**
     * @throws ArgsException
     */
    /**public function set(string $s): void
    {
        if (!is_numeric($s)) {
            throw new ArgsException(ErrorCode::INVALID_INTEGER, 'p', $s);
        }
        $this->intValue = (int) $s;
    }*/

    public function get(): int
    {
        return $this->intValue;
    }
}