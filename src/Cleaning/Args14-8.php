<?php
declare(strict_types=1);
//namespace CleanCode\Cleaning;

class Args
{
    private string $schema;
    private array $args;
    private bool $valid = true;
    private array $unexpectedArguments = [];
    private array $booleanArgs = [];
    private array $stringArgs = [];
    private array $intArgs = [];
    private array $argsFound = [];
    private int $currentArgument = 0;
    private string $errorArgumentId = '\0';
    private string $errorParameter = 'TILT';
    private \CleanCode\Listing14\ErrorCode $errorCode;

    public function __construct(string $schema, array $args)
    {
        $this->schema = $schema;
        $this->args = $args;
        $this->errorCode = \CleanCode\Listing14\ErrorCode::OK;
        $this->valid = $this->parse();
    }

    /**
     * @throws Exception
     */
    private function parse(): bool
    {
        if (empty($this->schema) && empty($this->args)) {
            return true;
        }
        $this->parseSchema();
        try {
            $this->parseArguments();
        } catch (ArgsException $e) {
        }

        return $this->valid;
    }

    private function parseSchema(): bool
    {
        foreach (explode(',', $this->schema) as $element) {
            if (!empty($element)) {
                $trimmedElement = trim($element);
                $this->parseSchemaElement($trimmedElement);
            }
        }
        return true;
    }

    private function parseSchemaElement(string $element): void
    {
        $elementId = $element[0];
        $elementTail = substr($element, 1);
        $this->validateSchemaElementId($elementId);

        if ($this->isBooleanSchemaElement($elementTail)) {
            $this->parseBooleanSchemaElement($elementId);
        } elseif ($this->isStringSchemaElement($elementTail)) {
            $this->parseStringSchemaElement($elementId);
        } elseif ($this->isIntegerSchemaElement($elementTail)) {
            $this->parseIntegerSchemaElement($elementId);
        } else {
            throw new ParseException("Argument: $elementId has invalid format: $elementTail.");
        }
    }

    private function validateSchemaElementId(string $elementId): void
    {
        if (!ctype_alpha($elementId)) {
            throw new ParseException("Bad character: $elementId in Args format: $this->schema");
        }
    }

    private function parseBooleanSchemaElement(string $elementId): void
    {
        $this->booleanArgs[$elementId] = false;
    }

    private function parseIntegerSchemaElement(string $elementId): void
    {
        $this->intArgs[$elementId] = 0;
    }

    private function parseStringSchemaElement(string $elementId): void
    {
        $this->stringArgs[$elementId] = '';
    }

    private function isStringSchemaElement(string $elementTail): bool
    {
        return $elementTail === '*';
    }

    private function isBooleanSchemaElement(string $elementTail): bool
    {
        return empty($elementTail);
    }

    private function isIntegerSchemaElement(string $elementTail): bool
    {
        return $elementTail === '#';
    }

    private function parseArguments(): bool
    {
        for ($this->currentArgument = 0; $this->currentArgument < count($this->args); $this->currentArgument++) {
            $arg = $this->args[$this->currentArgument];
            $this->parseArgument($arg);
        }
        return true;
    }

    private function parseArgument(string $arg): void
    {
        if (str_starts_with($arg, '-')) {
            $this->parseElements($arg);
        }
    }

    private function parseElements(string $arg): void
    {
        for ($i = 1; $i < strlen($arg); $i++) {
            $this->parseElement($arg[$i]);
        }
    }

    private function parseElement(string $argChar): void
    {
        if ($this->setArgument($argChar)) {
            $this->argsFound[] = $argChar;
        } else {
            $this->unexpectedArguments[] = $argChar;
            $this->errorCode = ErrorCode::UNEXPECTED_ARGUMENT;
            $this->valid = false;
        }
    }

    /**
     * @throws ArgsException
     */
    private function setArgument(string $argChar): bool
    {
        if ($this->isBooleanArg($argChar)) {
            $this->setBooleanArg($argChar, true);
        } elseif ($this->isStringArg($argChar)) {
            $this->setStringArg($argChar);
        } elseif ($this->isIntArg($argChar)) {
            $this->setIntArg($argChar);
        } else {
            return false;
        }

        return true;
    }

    private function isIntArg(string $argChar): bool
    {
        return isset($this->intArgs[$argChar]);
    }

    /**
     * @throws ArgsException
     */
    private function setIntArg(string $argChar): void
    {
        $this->currentArgument++;
        $parameter = $this->args[$this->currentArgument] ?? null;

        if ($parameter === null) {
            $this->valid = false;
            $this->errorArgumentId = $argChar;
            $this->errorCode = \CleanCode\Listing14\ErrorCode::MISSING_INTEGER;
            throw new ArgsException();
        }

        if (!is_numeric($parameter)) {
            $this->valid = false;
            $this->errorArgumentId = $argChar;
            $this->errorParameter = $parameter;
            $this->errorCode = \CleanCode\Listing14\ErrorCode::INVALID_INTEGER;
            throw new ArgsException();
        }

        $this->intArgs[$argChar] = (int)$parameter;
    }

    /**
     * @throws ArgsException
     */
    private function setStringArg(string $argChar): void
    {
        $this->currentArgument++;
        if (!isset($this->args[$this->currentArgument])) {
            $this->valid = false;
            $this->errorArgumentId = $argChar;
            $this->errorCode = \CleanCode\Listing14\ErrorCode::MISSING_STRING;
            throw new ArgsException();
        }

        $this->stringArgs[$argChar] = $this->args[$this->currentArgument];
    }

    private function isStringArg(string $argChar): bool
    {
        return isset($this->stringArgs[$argChar]);
    }

    private function setBooleanArg(string $argChar, bool $value): void
    {
        $this->booleanArgs[$argChar] = $value;
    }

    private function isBooleanArg(string $argChar): bool
    {
        return isset($this->booleanArgs[$argChar]);
    }

    public function cardinality(): int
    {
        return count($this->argsFound);
    }

    public function usage(): string
    {
        return $this->schema ? '-[' . $this->schema . ']' : '';
    }

    public function errorMessage(): string
    {
        return match ($this->errorCode) {
            ErrorCode::UNEXPECTED_ARGUMENT => $this->unexpectedArgumentMessage(),
            ErrorCode::MISSING_STRING => sprintf("Could not find string parameter for -%s.", $this->errorArgumentId),
            ErrorCode::INVALID_INTEGER => sprintf("Argument -%s expects an integer but was '%s'.", $this->errorArgumentId, $this->errorParameter),
            ErrorCode::MISSING_INTEGER => sprintf("Could not find integer parameter for -%s.", $this->errorArgumentId),
            default => ''
        };
    }

    private function unexpectedArgumentMessage(): string
    {
        return 'Argument(s) -' . implode('', $this->unexpectedArguments) . ' unexpected.';
    }

    private function falseIfNull(?bool $b): bool
    {
        return $b ?? false;
    }

    private function zeroIfNull(?int $i): int
    {
        return $i ?? 0;
    }

    private function blankIfNull(?string $s): string
    {
        return $s ?? '';
    }

    public function getString(string $arg): string
    {
        return $this->blankIfNull($this->stringArgs[$arg] ?? null);
    }

    public function getInt(string $arg): int
    {
        return $this->zeroIfNull($this->intArgs[$arg] ?? null);
    }

    public function getBoolean(string $arg): bool
    {
        return $this->falseIfNull($this->booleanArgs[$arg] ?? null);
    }

    public function has(string $arg): bool
    {
        return in_array($arg, $this->argsFound, true);
    }

    public function isValid(): bool
    {
        return $this->valid;
    }
}

/**

class ArgsException extends Exception{}

enum ErrorCode
{
    case OK;
    case MISSING_STRING;
    case MISSING_INTEGER;
    case INVALID_INTEGER;
    case UNEXPECTED_ARGUMENT;
}

class ParseException extends Exception
{
}

*/