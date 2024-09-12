<?php
declare(strict_types=1);
namespace CleanCode\Listing14;

use Exception;

class ArgsException extends Exception
{
    private string $errorArgumentId = '\0';
    private ?string $errorParameter = 'TILT';
    private ErrorCode $errorCode;

    /**
     * @throws Exception
     */
    public function __construct(ErrorCode $errorCode, ?string $errorArgumentId = null, ?string $errorParameter = null)
    {
        $this->errorCode = $errorCode;
        $this->errorParameter = $errorParameter;
        $this->errorArgumentId = $errorArgumentId;
        parent::__construct($this->errorMessage());
    }

    public function getErrorArgumentId(): string
    {
        return $this->errorArgumentId;
    }

    public function setErrorArgumentId(string $errorArgumentId): void
    {
        $this->errorArgumentId = $errorArgumentId;
    }

    public function getErrorParameter(): string
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
            ErrorCode::UNEXPECTED_ARGUMENT => sprintf('Argument -%s unexpected.', $this->errorArgumentId),
            ErrorCode::MISSING_STRING => sprintf('Could not find string parameter for -%s.', $this->errorArgumentId),
            ErrorCode::INVALID_INTEGER => sprintf($this->errorCode->value, $this->errorArgumentId, $this->errorParameter),
            ErrorCode::MISSING_INTEGER => sprintf('Could not find integer parameter for -%s.', $this->errorArgumentId),
            ErrorCode::INVALID_DOUBLE => sprintf("Argument -%s expects a double but was '%s'.", $this->errorArgumentId, $this->errorParameter),
            ErrorCode::MISSING_DOUBLE => sprintf('Could not find double parameter for -%s.', $this->errorArgumentId),
            default => '',
        };
    }
}

/**
class ArgsException extends Exception {

    public const UNEXPECTED_ARGUMENT = 2;
    public const INVALID_ARGUMENT_NAME = 3;
    public const INVALID_FORMAT = 1;
    public const MISSING_STRING = 4;
    public const INVALID_INTEGER = 5;
    public const MISSING_INTEGER = 6;
    const INVALID_DOUBLE = 7;
    const MISSING_DOUBLE = 8;

    private string $errorArgumentId = "\0";
    private string $errorParameter = "TILT";
    private ErrorCode $errorCode;

    public function __construct(string $message = "", ErrorCode $errorCode = ErrorCode::OK)
    {
        parent::__construct($message);
        $this->errorCode = $errorCode;
    }

    public function getErrorArgumentId(): string
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

    public function setErrorParameter(?string $errorParameter): void
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

}
 */