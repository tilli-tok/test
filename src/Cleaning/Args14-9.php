<?php
declare(strict_types=1);
//namespace CleanCode\Cleaning;

#use CleanCode\Cleaning\StringBuffer;

class Args
{
    private string $schema;
    private array $args;
    private bool $valid;
    private Set $unexpectedArguments;
    private array $booleanArgs = [];
    private int $numberOfArguments = 0;

    /**
     * @throws Exception
     */
    public function __construct(string $schema, array $args)
    {
        $this->schema = $schema;
        $this->args = $args;
        $this->unexpectedArguments = new Set();
        $this->valid = $this->parse();
    }

    public function isValid(): bool
    {
        return $this->valid;
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

        return count($this->unexpectedArguments->toArray()) === 0;
    }

    /**
     * @throws Exception
     */
    private function parseSchema(): void
    {
        foreach (explode(',', $this->schema) as $element) {
            $this->parseSchemaElement($element);
        }

    }

    private function parseSchemaElement(string $element): void
    {
        if (strlen($element) === 1) {
            $this->parseBooleanSchemaElement($element);
        }
    }

    private function parseBooleanSchemaElement(string $element): void
    {
        $char = $element[0];

        if (ctype_alpha($char)) {
            $this->booleanArgs[$char] = false;
        }
    }

    /**
     * @throws Exception
     */
    private function parseArguments(): void
    {
        foreach ($this->args as $arg) {
            $this->parseArgument($arg);
        }
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

    private function parseElement(string $argChar): void
    {
        if ($this->isBoolean($argChar)) {
            $this->numberOfArguments++;
            $this->setBooleanArg($argChar, true);
        } else {
            $this->unexpectedArguments->add($argChar);
        }
    }

    private function setBooleanArg(string $argChar, bool $value): void
    {
        $this->booleanArgs[$argChar] = $value;
    }

    private function isBoolean(string $argChar): bool
    {
        return isset($this->booleanArgs[$argChar]);
    }

    public function cardinality(): int
    {
        return $this->numberOfArguments;
    }

    public function usage(): string
    {
        if($this->schema > 0){
            return "-[" . $this->schema . "]";
        }else{
            return "";
        }
    }

    public function errorMessage(): string
    {
        if (count($this->unexpectedArguments->toArray()) > 0) {
            return $this->unexpectedArgumentMessage();
        }
        return '';
    }

    private function unexpectedArgumentMessage(): string
    {
        $message = new StringBuffer();
        $message = "Argument(s) -" . implode('', $this->unexpectedArguments->toArray());
        return $message . ' unexpected.';
    }

    public function getBoolean(string $arg): bool
    {
        return $this->booleanArgs[$arg] ?? false;
    }
}

class Set
{
    private array $set = [];

    public function add(mixed $value): void
    {
        $this->set[$value] = true;
    }

    public function toArray(): array
    {
        return array_keys($this->set);
    }
}