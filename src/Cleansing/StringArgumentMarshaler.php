<?php
declare(strict_types=1);
namespace CleanCode\Cleansing;

use Iterator;
use UnderflowException;

class StringArgumentMarshaler implements ArgumentMarshaler
{
    private string $stringValue = '';

    /**
     * @param Iterator $currentArgument
     * @return void
     * @throws ArgsException
     */
    public function set(Iterator $currentArgument): void
    {
        try {
            $this->stringValue = $currentArgument->current();
            $currentArgument->next();
        } catch (UnderflowException $e) {
            throw new ArgsException(ErrorCode::MISSING_STRING);
        }
    }

    public static function getValue(?ArgumentMarshaler $am): string
    {
        if ($am !== null && $am instanceof StringArgumentMarshaler) {
            return $am->stringValue;
        }else{
            return '';
        }
    }
}