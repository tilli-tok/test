<?php
declare(strict_types=1);
namespace CleanCode\Listing14;

use Exception;
use Throwable;

class Args
{
    private string $schema;
    private array $args;
    private bool $valid = true;
    private array $unexpectedArguments = [];
    private array $booleanArgs = [];
    private array $stringArgs = [];
    private array $argsFound = [];
    private int $currentArgument = 0;
    private string $errorArgument = '\0';
    private ErrorCode $errorCode = ErrorCode::OK;


    /**
     * @throws Exception
     */
    public function __construct(string $schema, array $args)
    {
        $this->schema = $schema;
        $this->args = $args;
        $this->valid = $this->parse();
    }

    /**
     * @throws Exception
     */
    private function parse(): bool
    {
        if (strlen($this->schema) === 0 && count($this->args) === 0) {
            return true;
        }

        $this->parseSchema();
        $this->parseArguments();

        return $this->valid;
    }

    /**
     * @throws Exception
     */
    private function parseSchema(): bool
    {
        foreach (explode(',', $this->schema) as $element) {
            if (strlen($element) > 0) {
                $this->parseSchemaElement(trim($element));
            }
        }

        return true;
    }

    /**
     * @throws Exception
     */
    private function parseSchemaElement(string $element): void
    {
        $elementId = $element[0];
        $elementTail = substr($element, 1);

        $this->validateSchemaElementId($elementId);

        if ($this->isBooleanSchemaElement($elementTail)) {
            $this->parseBooleanSchemaElement($elementId);
        } elseif ($this->isStringSchemaElement($elementTail)) {
            $this->parseStringSchemaElement($elementId);
        }
    }

    /**
     * @param string $elementId
     * @throws Exception
     */
    private function validateSchemaElementId(string $elementId): void
    {
        if (!ctype_alpha($elementId)) {
            throw new Exception("Bad character: $elementId in Args format: " . $this->schema);
        }
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
        return strlen($elementTail) === 0;
    }

    private function parseBooleanSchemaElement(string $elementId): void
    {
        $this->booleanArgs[$elementId] = false;
    }


    /**
     * @throws Exception
     */
    private function parseArguments(): bool
    {
        for ($this->currentArgument = 0; $this->currentArgument < count($this->args); $this->currentArgument++) {
            $arg = $this->args[$this->currentArgument];
            $this->parseArgument($arg);
        }
        return true;

    }

    /**
     * @throws Exception
     */
    private function parseArgument(string $arg): void
    {
        if (str_starts_with($arg, '-')) {
            $this->parseElements($arg);
        }
    }

    /**
     * @throws Exception
     */
    private function parseElements(string $arg): void
    {
        for ($i = 1; $i < strlen($arg); $i++) {
            $this->parseElement($arg[$i]);
        }
    }

    /**
     * @throws Exception
     */
    private function parseElement(string $argChar): void
    {
        if ($this->setArgument($argChar)) {
            $this->argsFound[] = $argChar;
        } else {
            $this->unexpectedArguments[] = $argChar;
            $this->valid = false;
        }
    }

    /**
     * @throws Exception
     */
    private function setArgument(string $argChar): bool
    {
        $set = true;
        if ($this->isBoolean($argChar)) {
            $this->setBooleanArg($argChar, true);
        } elseif ($this->isString($argChar)) {
            $this->setStringArg($argChar,'');
        } else {
            $set =  false;
        }

        return $set;
    }
    private function setStringArg(string $argChar, string $s): void
    {
        $this->currentArgument++;
        try {
            $this->stringArgs[$argChar] = $this->args[$this->currentArgument];
        } catch (ArrayIndexOutOfBoundsException $e) {
            $this->valid = false;
            $this->errorArgument = $argChar;
            $this->errorCode = ErrorCode::MISSING_STRING;
        }
    }
    private function isString(string $argChar): bool
    {
        return array_key_exists($argChar, $this->stringArgs);
    }

    private function setBooleanArg(string $argChar, bool $value): void
    {
        $this->booleanArgs[$argChar] = $value;
    }

    private function isBoolean(string $argChar): bool
    {
        return array_key_exists($argChar, $this->booleanArgs);
    }

    public function cardinality(): int
    {
        return count($this->argsFound);
    }

    public function usage(): string
    {
        if ($this->schema > 0) {
            return "-[" . $this->schema . "]";
        } else {
            return "";
        }
    }

    /**
     * @return string
     * @throws Exception
     */
    public function errorMessage(): string
    {
        if (count($this->unexpectedArguments) > 0) {
            return $this->unexpectedArgumentMessage();
        }

        return match ($this->errorCode) {
            ErrorCode::MISSING_STRING => sprintf("Could not find string parameter for -%c.", $this->errorArgument),
            ErrorCode::OK => throw new Exception("TILT: Should not get here."),
        };
    }

    private function unexpectedArgumentMessage(): string
    {
        $message = new StringBuffer();
        $message .= "Argument(s) -";
        foreach ($this->unexpectedArguments as $c) {
            $message .= $c;
        }
        $message .= " unexpected.";
        return $message;
    }

    public function getBoolean(string $arg): bool
    {
        return $this->booleanArgs[$arg] ?? false;
    }
    private function falseIfNull(Bool $b): bool {
        return !($b == null);
     }
    public function getString(string $arg): string
    {
        return $this->stringArgs[$arg] ?? '';
    }
    private function blankIfNull(String $s): string
    {
        return $s == null ? "" : $s;
     }
    public function has(string $arg): bool
    {
        return in_array($arg, $this->argsFound);
    }

    public function isValid(): bool
    {
        return $this->valid;
    }
}