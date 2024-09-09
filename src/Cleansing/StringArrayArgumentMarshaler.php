<?php
declare(strict_types=1);
namespace CleanCode\Cleansing;

use CleanCode\Cleansing\ArgumentMarshaler;
use Iterator;

class StringArrayArgumentMarshaler implements ArgumentMarshaler
{
    private array $stringArrayValue = [];

    public function set(Iterator $currentArgument): void
    {

    }

    public static function getValue(?ArgumentMarshaler $am): array
    {
        if ($am instanceof StringArrayArgumentMarshaler) {
            return $am->stringArrayValue;
        } else {
            return [];
        }
    }
}
