<?php
declare(strict_types=1);
namespace CleanCode\Cleansing;

use Iterator;

class IntegerArgumentMarshaler implements ArgumentMarshaler
{
    private int $intValue = 0;

    /**
     * @param Iterator $currentArgument
     * @return void
     * @throws ArgsException
     */
    public function set(\Iterator $currentArgument): void
    {
        $parameter = null;
        try {
            if ($currentArgument->valid()) {
                $parameter = $currentArgument->current();
                $this->intValue = (int)$parameter;
                $currentArgument->next();
            } else {
                throw new ArgsException(ErrorCode::MISSING_INTEGER);
            }
        } catch (NoSuchElementException $e) {
            throw new ArgsException(ErrorCode::MISSING_INTEGER);

        } catch (NumberFormatException $e) {
            throw new ArgsException(ErrorCode::INVALID_INTEGER, $parameter);
        }
    }

    public static function getValue(?ArgumentMarshaler $am): int
    {
        if ($am instanceof IntegerArgumentMarshaler) {
            return $am->intValue;
        }else {
            return 0;
        }
    }
}
