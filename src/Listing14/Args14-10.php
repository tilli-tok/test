<?php
declare(strict_types=1);
//namespace CleanCode\Listing14;

//use ArgsException;
//use Exception;
//use ParseException;

class Args
{
    private string $schema;
    private array $args;
    private bool $valid = true;
    private array $unexpectedArguments = [];
    //private array $booleanArgs = [];
    //private array $intArgs = [];
    //private array $stringArgs = [];
    private array $argsFound = [];
    private int $currentArgument = 0;
    private string $errorArgument = '\0';
    //private string $errorArgumentId = '\0';
    //private string $errorParameter = 'TILT';
    private ErrorCode $errorCode = ErrorCode::OK;
    private array $marshalers = [];


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
            //$this->parseBooleanSchemaElement($elementId);
            $this->marshalers[$elementId] = new BooleanArgumentMarshaler();
        } elseif ($this->isStringSchemaElement($elementTail)) {
            //$this->parseStringSchemaElement($elementId);
            $this->marshalers[$elementId] = new StringArgumentMarshaler();
        } elseif ($this->isIntegerSchemaElement($elementTail)) {
            //$this->IntegerArgumentMarshaler($elementId);
            $this->marshalers[$elementId] = new IntegerArgumentMarshaler();
        } else {
            throw new ParseException(sprintf(
                "Argument: %c has invalid format: %s.", $elementId, $elementTail), 0);
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


    private function isStringSchemaElement(string $elementTail): bool
    {
        return $elementTail === '*';
    }

    private function isBooleanSchemaElement(string $elementTail): bool
    {
        return strlen($elementTail) === 0;
    }

    private function isIntegerSchemaElement(string $elementTail): bool
    {
        return $elementTail === '';
    }
    private function parseBooleanSchemaElement(string $elementId): void
    {
        //$this->booleanArgs[$elementId] = false;
        //$this->booleanArgs[$elementId] = new BooleanArgumentMarshaler();
        //$m = new BooleanArgumentMarshaler();
        //$booleanArgs[$elementId] = $m;
        //$marshalers[$elementId] = $m;

        $this->marshalers[$elementId] = new BooleanArgumentMarshaler();
    }
    private function parseIntegerSchemaElement(string $elementId): void
    {
        //$this->intArgs[$elementId] = new IntegerArgumentMarshaler();
        //$m = new IntegerArgumentMarshaler();
        //$intArgs[$elementId] = $m;
        //$marshalers[$elementId] = $m;

        $this->marshalers[$elementId] = new StringArgumentMarshaler();
    }
    private function parseStringSchemaElement(string $elementId): void
    {
        //$this->stringArgs[$elementId] = '';
        //$this->stringArgs[$elementId] = new StringArgumentMarshaler();$m = new StringArgumentMarshaler();
        //$m = new StringArgumentMarshaler();
        ///$stringArgs[$elementId] = $m;
        //$marshalers[$elementId] = $m;

        $this->marshalers[$elementId] = new StringArgumentMarshaler();
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
        /**
        $set = true;
        if ($this->isBoolean($argChar)) {
        $this->setBooleanArg($argChar, true);
        } elseif ($this->isString($argChar)) {
        $this->setStringArg($argChar,'');
        } else {
        $set =  false;
        }
        return $set;*/

        /**
        $m = $this->marshalers[$argChar];
        if ($this->isBooleanArg($m))
        $this->setBooleanArg($argChar);
        else if ($this->isStringArg($m))
        $this->setStringArg($argChar);
        else if ($this->isIntArg($m))
        $this->setIntArg($argChar);
        else
        return false;
        return true;*/

        /**
        if ($m instanceof BooleanArgumentMarshaler)
        //$this->setBooleanArg($argChar);
        $this->setBooleanArg($m);
        else if ($m instanceof StringArgumentMarshaler)
        $this->setStringArg($argChar);
        else if ($m instanceof IntegerArgumentMarshaler)
        $this->setIntArg($argChar);
        else
        return false;
        return true;*/
//Duplicated code fragment (17 lines long)
// Inspection info: Reports duplicated blocks of code from the selected scope: the same file or the entire project.
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
        }*/
        return true;
    }

    /**
     * @throws ArgsException
     */
    //private function setStringArg(string $argChar, string $s): void
    private function setStringArg(ArgumentMarshaler $m): void
    {
        $this->currentArgument++;
        try {
            //$this->stringArgs[$argChar] = $this->args[$this->currentArgument];
            //$this->stringArgs[$argChar]->setString($this->args[$this->currentArgument]);
            //$this->stringArgs[$argChar]->set($this->args[$this->currentArgument]);
            $m->set($this->args[$this->currentArgument]);
        } catch (ArrayIndexOutOfBoundsException $e) {
            //$this->valid = false;
            //$this->errorArgument = $argChar;
            $this->errorCode = ErrorCode::MISSING_STRING;
            throw new ArgsException();
        }
    }
    private function isString(string $argChar): bool
    {
        return array_key_exists($argChar, $this->stringArgs);
    }

    //private function setBooleanArg(string $argChar, bool $value): void
    private function setBooleanArg(ArgumentMarshaler $m): void
    {
        //$this->booleanArgs[$argChar] = $value;
        //$this->booleanArgs[$argChar]->setBoolean($value);
        try {
            $m->set('true'); //$this->booleanArgs[$argChar]->set("true");
        } catch (ArgsException $e) {
        }

    }

    /**
     * @param string $argChar
     * @return void
     * @throws ArgsException
     */
    //private function setIntArg(string $argChar): void
    private function setIntArg(ArgumentMarshaler $m): void
    {
        $this->currentArgument++;
        //$parameter = null;
        try {
            $parameter = $this->args[$this->currentArgument];
            //$this->intArgs[$argChar]->setInteger(intval($parameter));
            //$this->intArgs[$argChar]->set($parameter);
            $m->set($parameter);
        } catch (ArrayIndexOutOfBoundsException $e) {
            //$this->valid = false;
            //$this->errorArgumentId = $argChar;
            $this->errorCode = ErrorCode::MISSING_INTEGER;
            throw new ArgsException();
        } catch (ArgsException $e) {
            //$this->valid = false;
            //$this->errorArgumentId = $argChar;
            $this->errorParameter = $parameter;
            $this->errorCode = ErrorCode::INVALID_INTEGER;
            throw $e;
        }
    }
    private function isBoolean(string $argChar): bool
    {
        return array_key_exists($argChar, $this->booleanArgs);
    }
    /**
    private function isBooleanArg(string $argChar): bool {
    $m = $this->marshalers[$argChar];
    return $m instanceof BooleanArgumentMarshaler;
    }
    private function isIntArg(string $argChar): bool
    {
    $m = $this->marshalers[$argChar];
    return $m instanceof IntegerArgumentMarshaler;
    }
    private function isStringArg(string $argChar): bool
    {
    $m = $this->marshalers[$argChar];
    return $m instanceof StringArgumentMarshaler;
    }*/


    /**
    private function isIntArg(ArgumentMarshaler $m): bool
    {
    return $m instanceof IntegerArgumentMarshaler;
    }
    private function isStringArg(ArgumentMarshaler $m): bool
    {
    return $m instanceof StringArgumentMarshaler;
    }
    private function isBooleanArg(ArgumentMarshaler $m): bool
    {
    return $m instanceof BooleanArgumentMarshaler;
    }*/

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
        //return $this->booleanArgs[$arg] ?? false;
        //return !is_null($this->booleanArgs[$arg]->getBoolean());

        //return $this->booleanArgs[$arg]->getBoolean();

        //$am = $this->booleanArgs[$arg];
        //return !is_null($am) && $am->getBoolean();

        //$am = $this->booleanArgs[$arg];
        //return $am != null && $am->get();

        $am = $this->marshalers[$arg];
        $b = false;
        try {
            $b = $am != null && $am->get();
        } catch (ClassCastException $e) {
            $b = false;
        }
        return $b;
    }
    private function falseIfNull(Bool $b): bool {
        return !($b == null);
    }
    public function getString(string $arg): string
    {
        //return $this->stringArgs[$arg] ?? '';
        //$am = $this->stringArgs[$arg];
        //return !is_null($am) ? $am->getString() : '';
        //return !is_null($am) ? $am->get() : '';
        //return (string)$am?->get();

        $am = $this->marshalers[$arg] ?? null;
        try {
            return $am == null ? "" : (string) $am->get();
        } catch (ClassCastException $e) {
            return "";
        }


    }
    public function getInt(string $arg): int
    {
        $am = $this->intArgs[$arg];
        //return $am == null ? 0 : $am->getInteger();
        return $am == null ? 0 : (Int) $am->get();
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