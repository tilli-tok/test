<?php
declare(strict_types=1);
namespace CleanCode\Cleansing;

class ArgsException extends \Exception {
    private string $errorArgumentId = "\0";
    private ?string $errorParameter = null;
    private ErrorCode $errorCode;


    public function __construct(ErrorCode $errorCode, string $errorArgumentId, ?string $errorParameter)
    {
        $this->errorCode = $errorCode;
        $this->errorParameter = $errorParameter;
        $this->errorArgumentId = $errorArgumentId;
        //ArgsException::__construct($this->errorMessage());
    }


    public function argsExceptionWithMessage(string $message)
    {
        return ArgsException::__construct($message);
    }

   public function argsExceptionWithErrorCode(ErrorCode $errorCode): string
   {
       $this->errorCode = $errorCode;
   }
   public function argsExceptionWithErrorCodeAndParameter(ErrorCode $errorCode, string $errorParameter): string
   {
       $this->errorCode = $errorCode;
       $this->errorParameter = $errorParameter;
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

    public function errorMessage(): string {
        return match ($this->errorCode) {
            ErrorCode::OK => "TILT: Should not get here.",
            ErrorCode::UNEXPECTED_ARGUMENT => sprintf("Argument -%c unexpected.", $this->errorArgumentId),
            ErrorCode::MISSING_STRING => sprintf("Could not find string parameter for -%c.", $this->errorArgumentId),
            ErrorCode::INVALID_INTEGER => sprintf("Argument -%c expects an integer but was '%s'.", $this->errorArgumentId, $this->errorParameter),
            ErrorCode::MISSING_INTEGER => sprintf("Could not find integer parameter for -%c.", $this->errorArgumentId),
            ErrorCode::INVALID_DOUBLE => sprintf("Argument -%c expects a double but was '%s'.", $this->errorArgumentId, $this->errorParameter),
            ErrorCode::MISSING_DOUBLE => sprintf("Could not find double parameter for -%c.", $this->errorArgumentId),
            ErrorCode::INVALID_ARGUMENT_NAME => sprintf("'%c' is not a valid argument name.", $this->errorArgumentId),
            ErrorCode::INVALID_ARGUMENT_FORMAT => sprintf("'%s' is not a valid argument format.", $this->errorParameter),
        };
    }
}

