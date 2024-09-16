<?php
declare(strict_types=1);

namespace CleanCode\Listing14\Marshaler;

use CleanCode\Listing14\ArgsException;
use CleanCode\Listing14\ErrorCode;
use CleanCode\Listing14\NumberFormatException;
use Iterator;
use OutOfBoundsException;

abstract class ArgumentMarshaler
{


    /**
     * @throws ArgsException
     */
    public function set(Iterator $currentArgument): void
    {
        $parameter = $currentArgument->current();
        try {
            $this->ensureValidArgument($currentArgument);
            $this->validateParameter($parameter);
            $this->setTypedParameter($parameter);
            $currentArgument->next();
        } catch (OutOfBoundsException) {
            throw new ArgsException($this->getMissingErrorCode());
        } catch (NumberFormatException) {
            throw new ArgsException(errorCode: $this->getInvalidErrorCode(), errorParameter: $parameter);
        }
    }

    abstract protected function setTypedParameter(mixed $parameter);

    protected function validateParameter(mixed $parameter): void
    {

    }

    public abstract function get(): mixed;


    protected function getMissingErrorCode(): ?ErrorCode
    {
        return null;
    }

    protected function getInvalidErrorCode(): ?ErrorCode
    {
        return null;
    }

    /**
     * @throws OutOfBoundsException
     */
    protected function ensureValidArgument(Iterator $currentArgument): void
    {
        if(!$this->getMissingErrorCode()){
            return;
        }
        if (!$currentArgument->valid()) {
            throw new OutOfBoundsException($this->getMissingErrorCode()->value);
        }
    }

}
