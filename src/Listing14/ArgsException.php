<?php
declare(strict_types=1);
namespace CleanCode\Listing14;


/**
 * class ArgsException extends \Exception {
 *
* public function __construct(ErrorCode $errorCode, ?string $errorArgumentId = null, ?string $errorParameter = null)
    * {
        * parent::__construct();
    * }
 *
* }
 */


class ArgsException extends \Exception {

    private string $errorArgumentId = "\0";
    private ?string $errorParameter = null;
    private ErrorCode $errorCode;

    /**
     * @param ErrorCode $errorCode
     * @param string|null $errorArgumentId
     * @param string|null $errorParameter
     */

    public function __construct(ErrorCode $errorCode, ?string $errorArgumentId = null, ?string $errorParameter = null)
    {
        parent::__construct();
        $this->errorCode = $errorCode;
        $this->errorParameter = $errorParameter;
        $this->errorArgumentId = $errorArgumentId;
        return \CleanCode\Cleansing\ArgsException::__construct($errorCode);
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
            ErrorCode::MISSING_STRING => sprintf("Could not find string parameter for -%c.", $this->errorArgumentId),
            ErrorCode::INVALID_INTEGER => sprintf("Argument -%c expects an integer but was '%s'.", $this->errorArgumentId, $this->errorParameter),
            ErrorCode::MISSING_INTEGER => sprintf("Could not find integer parameter for -%c.", $this->errorArgumentId),
         };
    }
}