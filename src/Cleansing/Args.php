<?php
declare(strict_types=1);

namespace CleanCode\Cleansing;

use ArrayIterator;

use Ds\Map;
use Ds\Set;

class Args
{
    /**
     * @param Map<string, ArgumentMarshaler> $marshalers
     * @param Set<string> $argsFound
     */
    private Map $marshalers;
    private Set $argsFound;
    private ArrayIterator $currentArgument;

    /**
     * @param string $schema
     * @throws ArgsException
     * @var string[] $args
     */
    public function __construct(string $schema, array $args)
    {
        $this->marshalers = new Map();
        $this->argsFound = new Set();
        $this->currentArgument = new ArrayIterator($args);
        $this->parseSchema($schema);
        $this->parseArgumentStrings($args);
    }

    /**
     * @throws ArgsException
     */
    private function parseSchema(string $schema): void
    {
        $elements = explode(',', $schema);
        foreach ($elements as $element) {
            if (strlen(trim($element)) > 0) {
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

        $this->marshalers[$elementId] = match ($elementTail) {
            '' => new BooleanArgumentMarshaler(),
            '*' => new StringArgumentMarshaler(),
            '#' => new IntegerArgumentMarshaler(),
            '##' => new DoubleArgumentMarshaler(),
            '[*]' => new StringArrayArgumentMarshaler(),
            default => throw new ArgsException(ErrorCode::INVALID_ARGUMENT_FORMAT, $elementId, null)
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
    private function parseArgumentStrings(array $argsList): void
    {
        $currentArgument = new ArrayIterator($argsList);

        foreach ($currentArgument as $argString) {
            if (str_starts_with($argString, "-")) {
                self::parseArgumentCharacters(substr($argString, 1));
            } else {
                $currentArgument->rewind();
                break;
            }
        }
    }

    /**
     * @throws ArgsException
     */
    private function parseArgumentCharacters(string $argChars): void
    {
        for ($i = 0; $i < strlen($argChars); $i++) {
            $this->parseArgumentCharacter($argChars[$i]);
        }
    }

    /**
     * @throws ArgsException
     */
    private function parseArgumentCharacter(string $argChar): void
    {
        $m = $this->marshalers[$argChar] ?? null;
        if ($m === null) {
            throw new ArgsException(ErrorCode::UNEXPECTED_ARGUMENT, $argChar, null);
        } else {
            $this->argsFound[] = $argChar;
            try {
                $m->set($this->currentArgument);
            } catch (ArgsException $e) {
                $e->setErrorArgumentId($argChar);
                throw $e;
            }
        }
    }

    public function has(string $arg): bool
    {
        return in_array($arg, (array)$this->argsFound, true);
    }

    public function nextArgument(): int
    {
        return $this->currentArgument->key();
    }

    public function getBoolean(string $arg): bool
    {
        return BooleanArgumentMarshaler::getValue($this->marshalers[$arg]);
    }

    public function getString(string $arg): ?string
    {
        return StringArgumentMarshaler::getValue($this->marshalers[$arg] ?? null);
    }

    public function getInt(string $arg): int
    {
        return IntegerArgumentMarshaler::getValue($this->marshalers[$arg] ?? null);
    }

    public function getDouble(string $arg): float
    {
        return DoubleArgumentMarshaler::getValue($this->marshalers[$arg] ?? null);
    }

    public function getStringArray(string $arg): array
    {
        return StringArrayArgumentMarshaler::getValue($this->marshalers[$arg] ?? null);
    }
}