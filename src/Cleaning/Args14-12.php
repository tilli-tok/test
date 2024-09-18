<?php
declare(strict_types=1);
//namespace CleanCode\Listing14;

//use Exception;
//use OutOfBoundsException;

class Args
{
    private string $schema;
    private array $args;
    private bool $valid = true;
    private array $unexpectedArguments = [];
    private array $argsFound = [];
    private array $marshalers = [];
    private int $currentArgument = 0;
    private string $errorArgument = '\0';
    private string $errorArgumentId = '\0';
    private string $errorParameter = "TILT";
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
     * @return bool
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
/**
        $elementId = $element[0];
        $elementTail = substr($element, 1);
        $this->validateSchemaElementId($elementId);

        if ($this->isBooleanSchemaElement($elementTail)) {
            $this->marshalers[$elementId] = new BooleanArgumentMarshaler();
        } elseif ($this->isStringSchemaElement($elementTail)) {
            $this->marshalers[$elementId] = new StringArgumentMarshaler();
        } elseif ($this->isIntegerSchemaElement($elementTail)) {
            $this->marshalers[$elementId] = new IntegerArgumentMarshaler();
        } else {
            throw new Exception(sprintf(
                "Argument: '%s' has invalid format: '%s'.", $elementId, $elementTail), 0);
        }*/
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

    private function isStringSchemaElement(string $elementTail): bool
    {
        return $elementTail === '*';
    }

    private function isBooleanSchemaElement(string $elementTail): bool
    {
        return $elementTail === '';
    }

    private function isIntegerSchemaElement(string $elementTail): bool
    {
        return $elementTail === '#';
    }
    private function parseBooleanSchemaElement(string $elementId): void
    {
        $this->marshalers[$elementId] = new BooleanArgumentMarshaler();
    }
    private function parseIntegerSchemaElement(string $elementId): void
    {
        $this->marshalers[$elementId] = new StringArgumentMarshaler();
    }
    private function parseStringSchemaElement(string $elementId): void
    {
        $this->marshalers[$elementId] = new StringArgumentMarshaler();
    }

    /**
     * @return bool
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
        /**
        $m = $this->marshalers[$argChar];

        try {
            if ($m instanceof BooleanArgumentMarshaler)
                $this->setBooleanArg($m);
            else if ($m instanceof StringArgumentMarshaler)
                $this->setStringArg($m);
            else if ($m instanceof IntegerArgumentMarshaler)
                $this->setIntArg($m);
            else
                return false;
        } catch (ArgsException $e) {
            $this->valid = false;
            $this->errorArgumentId = $argChar;
            throw $e;
        }
        return true;*/
    }

    /**
     * @throws ArgsException
     */
    private function setStringArg(ArgumentMarshaler $m): void
    {
        $this->currentArgument++;
        try {
            $m->set($this->args[$this->currentArgument]);
        } catch (OutOfBoundsException $e) {
            $this->errorCode = ErrorCode::MISSING_STRING;
            throw new ArgsException($this->errorCode);
        }
    }

    private function setBooleanArg(ArgumentMarshaler $m): void
    {
        try {
            $m->set('true');
        } catch (ArgsException $e) {
        }

    }

    /**
     * @throws ArgsException
     */
    private function setIntArg(ArgumentMarshaler $m): void
    {
        $this->currentArgument++;
        try {
            $parameter = $this->args[$this->currentArgument];
            $m->set($parameter);
        } catch (OutOfBoundsException $e) {
            $this->errorCode = ErrorCode::MISSING_INTEGER;
            throw new ArgsException($this->errorCode);
        } catch (ArgsException $e) {
            $this->errorParameter = $parameter;
            $this->errorCode = ErrorCode::INVALID_INTEGER;
            throw $e;
        }
    }

    public function cardinality(): int
    {
        return count($this->argsFound);
    }

    public function usage(): string
    {
        if (strlen($this->schema) > 0) {
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
            ErrorCode::INVALID_INTEGER, ErrorCode::MISSING_INTEGER => throw new \Exception('To be implemented'),
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
        $am = $this->marshalers[$arg];
        try {
            return $am != null && $am->get();
        } catch (Exception  $e) {
            return false;
        }
    }
    private function falseIfNull(Bool $b): bool {
        return !($b == null);
    }
    public function getString(string $arg): string
    {
        $am = $this->marshalers[$arg] ?? null;
        try {
            return $am == null ? "" : (string) $am->get();
        } catch (Exception  $e) {
            return "";
        }


    }
    public function getInt(string $arg): int
    {
        $am = $this->marshalers[$arg] ?? null;
        return $am == null ? 0 : (int) $am->get();
    }
    private function blankIfNull(string $s): string
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