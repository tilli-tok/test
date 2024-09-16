<?php
declare(strict_types=1);

namespace CleanCode\Listing14;

use Exception;

class ArgsException extends Exception
{
    /**
     * @throws Exception
     */
    public function __construct(
        private ErrorCode $errorCode,
        private ?string   $errorArgumentId = '\0',
        private ?string   $errorParameter = 'TILT')
    {
        parent::__construct($errorCode->value);
    }

    public function getErrorArgumentId(): ?string
    {
        return $this->errorArgumentId;
    }

    public function setErrorArgumentId(string $errorArgumentId): void
    {
        $this->errorArgumentId = $errorArgumentId;
    }

    public function getErrorParameter(): ?string
    {
        return $this->errorParameter;
    }

    public function setErrorParameter(string $errorParameter): void
    {
        $this->errorParameter = $errorParameter;
    }

    public function getErrorCode(): ErrorCode
    {
        return $this->errorCode;
    }

    public function setErrorCode(ErrorCode $errorCode): void
    {
        $this->errorCode = $errorCode;
    }

    /**
     * @throws Exception
     */
    public function errorMessage(): string
    {
        return match ($this->errorCode) {
            ErrorCode::OK => throw new Exception($this->errorCode->value),
            ErrorCode::UNEXPECTED_ARGUMENT => sprintf($this->errorCode->value, $this->errorArgumentId),
            ErrorCode::MISSING_STRING => sprintf($this->errorCode->value, $this->errorArgumentId),
            ErrorCode::INVALID_INTEGER => sprintf($this->errorCode->value, $this->errorArgumentId, $this->errorParameter),
            ErrorCode::MISSING_INTEGER => sprintf($this->errorCode->value, $this->errorArgumentId),
            ErrorCode::INVALID_DOUBLE => sprintf($this->errorCode->value, $this->errorArgumentId, $this->errorParameter),
            ErrorCode::MISSING_DOUBLE => sprintf($this->errorCode->value, $this->errorArgumentId),
            default => '',
        };
        /**
        return sprintf(
            $this->errorCode->value,
            $this->errorArgumentId,
            $this->errorParameter
        );*/
    }
}