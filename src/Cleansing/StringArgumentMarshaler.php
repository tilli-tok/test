<?php
declare(strict_types=1);

namespace CleanCode\Cleansing;

use Iterator;
use UnderflowException;

class StringArgumentMarshaler implements ArgumentMarshaler
{
    private string $stringValue = '';

    /**
     * @throws ArgsException
     */
    public function set(Iterator $currentArgument): void
    {
        try {
            $this->stringValue = $currentArgument->current();
            $currentArgument->next();
        } catch (UnderflowException) {
            throw new ArgsException(ErrorCode::MISSING_STRING);
        }
    }

    public static function getValue(?ArgumentMarshaler $am): string
    {
        if ($am instanceof StringArgumentMarshaler) {
            return $am->stringValue;
        } else {
            return '';
        }
    }
}