<?php
declare(strict_types=1);
//namespace CleanCode\Listing14;

//use CleanCode\Cleansing\NoSuchElementException;
//use Exception;
//use Iterator;

class Args
{
    private string $schema;
    //private array $args;
    private bool $valid = true;
    private array $unexpectedArguments = [];
    private array $argsFound = [];
    private array $marshalers = [];
    private Iterator $currentArgument;
    private string $errorArgument = '\0';
    private string $errorArgumentId = '\0';
    private string $errorParameter = "TILT";
    private array $argsList = [];
    private ErrorCode $errorCode = ErrorCode::OK;


    /**
     * @throws Exception
     */
    public function __construct(string $schema, array $args)
    {
        $this->schema = $schema;
        //$this->args = $args;
        $this->argsList = $args;
        $this->valid = $this->parse();
    }

    /**
     * @throws Exception
     */
    private function parse(): bool
    {
        if (strlen($this->schema) === 0 && count($this->argsList) === 0) {
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

        $elementId = $element[0];
        $elementTail = substr($element, 1);
        $this->validateSchemaElementId($elementId);

        if (strlen($elementTail) === 0) {
            $this->marshalers[$elementId] = new BooleanArgumentMarshaler();
        } elseif ($elementTail === '*') {
            $this->marshalers[$elementId] = new StringArgumentMarshaler();
        } elseif ($elementTail === '#') {
            $this->marshalers[$elementId] = new DoubleArgumentMarshaler();
        } elseif ($elementTail === '##') {
            $this->marshalers[$elementId] = new DoubleArgumentMarshaler();
        } else {
            throw new Exception(
                sprintf("Argument: %s has invalid format: %s.", $elementId, $elementTail)
            );
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
        //for ($this->currentArgument = 0; $this->currentArgument < count($this->args); $this->currentArgument++) {
        //    $arg = $this->args[$this->currentArgument];
        //    $this->parseArgument($arg);
        /**for ($this->currentArgument = $this->argsList; $this->currentArgument++) {
            $arg = $this->currentArgument->next();
            $this->parseArgument((string)$arg);
        }*/
        foreach ($this->argsList as $arg) {
            $this->parseArgument((string)$arg);
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
        $m = $this->marshalers[$argChar];
        if ($m == null)
            return false;
        try {
            $m->set($this->currentArgument);
            return true;
            /**
            if ($m instanceof BooleanArgumentMarshaler)
                //$this->setBooleanArg($m, $this->currentArgument);
                $m->set($this->currentArgument);
            else if ($m instanceof StringArgumentMarshaler)
                //$this->setStringArg($m);
                $m->set($this->currentArgument);
            else if ($m instanceof IntegerArgumentMarshaler)
                //$this->setIntArg($m);
                $m->set($this->currentArgument);
            //else
                //return false;*/
        } catch (ArgsException $e) {
            $this->valid = false;
            $this->errorArgumentId = $argChar;
            throw $e;
        }
        //return true;
    }

    /**
     * @throws ArgsException
     */
    private function setStringArg(ArgumentMarshaler $m): void
    {
        $this->currentArgument++;
        try {
            //$m->set($this->args[$this->currentArgument]);
            //$m->set($this->currentArgument->next());
            $parameter = $this->currentArgument->next();
            $m->set((string)$parameter);
        //} catch (OutOfBoundsException $e) {
        } catch (NoSuchElementException $e) {
            $this->errorCode = ErrorCode::MISSING_STRING;
            throw new ArgsException();
        }
    }

    /**private function setBooleanArg(ArgumentMarshaler $m,
                                   Iterator $currentArgument): void
    {
        try {
            $m->set('true');
        } catch (ArgsException $e) {
        }
    }*/

    /**
     * @throws ArgsException
     */
   private function setIntArg(ArgumentMarshaler $m): void
    {
        $this->currentArgument++;
        $parameter = '';
        try {
            //$parameter = $this->args[$this->currentArgument];
            $parameter = $this->currentArgument->next();
            $m->set((string)$parameter);
        //} catch (OutOfBoundsException $e) {
        } catch (NoSuchElementException $e) {
            $this->errorCode = ErrorCode::MISSING_INTEGER;
            throw new ArgsException();
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
        return match ($this->errorCode) {
            ErrorCode::OK => throw new Exception("TILT: Should not get here."),
            ErrorCode::UNEXPECTED_ARGUMENT => $this->unexpectedArgumentMessage(),
            ErrorCode::MISSING_STRING => sprintf("Could not find string parameter for -%s.", $this->errorArgumentId),
            ErrorCode::INVALID_INTEGER => sprintf("Argument -%s expects an integer but was '%s'.", $this->errorArgumentId, $this->errorParameter),
            ErrorCode::MISSING_INTEGER => sprintf("Could not find integer parameter for -%s.", $this->errorArgumentId),
            ErrorCode::INVALID_DOUBLE => sprintf("Argument -%s expects a double but was '%s'.", $this->errorArgumentId, $this->errorParameter),
            ErrorCode::MISSING_DOUBLE => sprintf("Could not find double parameter for -%s.", $this->errorArgumentId),
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
    public function getDouble(string $arg): float {
        $am = $this->marshalers[$arg];
         try {
            return $am == null ? 0 : (float) $am->get();
         } catch (Exception) {
            return 0.0;
        }
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