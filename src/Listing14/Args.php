<?php
declare(strict_types=1);
namespace CleanCode\Listing14;

use ArrayIterator;
use Exception;
use Iterator;

class Args
{
    private string $schema;
    private array $marshalers = [];
    private array $argsFound = [];
    private ?Iterator $currentArgument = null;
    private array $argsList;

    /**
     * @throws ArgsException
     */
    public function __construct(string $schema, array $args)
    {
        $this->schema = $schema;
        $this->argsList = $args;
        $this->parse();
    }

    /**
     * @throws ArgsException
     */
    private function parse(): void
    {
        $this->parseSchema();
        $this->parseArguments();
    }

    /**
     * @throws ArgsException
     */
    private function parseSchema(): void
    {
        foreach (explode(',', $this->schema) as $element) {
            if (!empty($element)) {
                $this->parseSchemaElement(trim($element));
            }
        }
    }

    /**
     * @throws ArgsException
     */
    private function parseSchemaElement(string $element): void
    {
        $elementId = $element[0];
        $elementTail = substr($element, 1);

        $this->validateSchemaElementId($elementId);

        match ($elementTail) {
            '' => $this->marshalers[$elementId] = new BooleanArgumentMarshaler(),
            '*' => $this->marshalers[$elementId] = new StringArgumentMarshaler(),
            '#' => $this->marshalers[$elementId] = new IntegerArgumentMarshaler(),
            '##' => $this->marshalers[$elementId] = new DoubleArgumentMarshaler(),
            default => throw new ArgsException(ErrorCode::INVALID_FORMAT, $elementId, $elementTail),
        };
    }

    /**
     * @throws ArgsException
     */
    private function validateSchemaElementId(string $elementId): void
    {
        if (!ctype_alpha($elementId)) {
            throw new ArgsException(ErrorCode::INVALID_ARGUMENT_NAME, $elementId, null);
        }
    }

    /**
     * @throws ArgsException
     */
    private function parseArguments(): void
    {
        $this->currentArgument = new ArrayIterator($this->argsList);
        while ($this->currentArgument->valid()) {
            $arg = $this->currentArgument->current();
            $this->parseArgument($arg);
            $this->currentArgument->next();
        }

    }

    /**
     * @throws ArgsException
     */
    private function parseArgument(string $arg): void
    {
        if (str_starts_with($arg, '-')) {
            $this->parseElements($arg);
        }
    }

    /**
     * @throws ArgsException
     */
    private function parseElements(string $arg): void
    {
        for ($i = 1; $i < strlen($arg); $i++) {
            $this->parseElement($arg[$i]);
        }
    }

    /**
     * @throws ArgsException
     */
    private function parseElement(string $argChar): void
    {
        if ($this->setArgument($argChar)) {
            $this->argsFound[] = $argChar;
        } else {
            throw new ArgsException(ErrorCode::UNEXPECTED_ARGUMENT, $argChar, null);
        }
    }

    /**
     * @throws ArgsException
     */
    private function setArgument(?string $argChar): bool
    {
        $m = $this->marshalers[$argChar];

        if ($m === null) {
            return false;
        }

        try {
            $this->currentArgument->next();
            $m->set($this->currentArgument);
            return true;
        } catch (ArgsException $e) {
            $e->setErrorArgumentId($argChar);
            throw $e;
        }
    }

    public function cardinality(): int
    {
        return count($this->argsFound);
    }

    public function usage(): string
    {
        if(!empty($this->schema)){
            return "-[".$this->schema."]";
        }else{
            return "";
        }
    }

    public function getBoolean(string $arg): bool
    {
        $am = $this->marshalers[$arg];
        //$b = false;
        try {
            return $am !== null && $am->get();
        } catch (Exception) {
            //$b = false;
        }
        //return $b;
        return false;
    }

    public function getString(string $arg): string
    {
        $am = $this->marshalers[$arg];
        try {
            return $am == null ? "" : (string) $am->get();
        } catch (Exception) {
            return "";
        }
    }

    public function getInt(string $arg): int
    {
        $am = $this->marshalers[$arg];
        try {
            return $am == null ? 0 : (int) $am->get();
        } catch (Exception) {
            return 0;
        }
    }

    public function getDouble(string $arg): float
    {
        $am = $this->marshalers[$arg];
        try {
            return $am == null ? 0 : (float) $am->get();
        } catch (Exception) {
            return 0.0;
        }
    }

    public function has(string $arg): bool
    {
        return in_array($arg, $this->argsFound,true);
    }
}
