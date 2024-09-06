<?php
declare(strict_types=1);
namespace CleanCode\Cleansing;

use Iterator;

class BooleanArgumentMarshaler implements ArgumentMarshaler
{
    private bool $booleanValue = false;

    public function set(Iterator $currentArgument): void
    //throws ArgsException
    {
        $this->booleanValue = true;
    }
    public static function getValue(?ArgumentMarshaler $am): bool
    {
        if ($am !== null && $am instanceof BooleanArgumentMarshaler) {
            return $am->booleanValue;
        } else {
            return false;
        }
    }
}