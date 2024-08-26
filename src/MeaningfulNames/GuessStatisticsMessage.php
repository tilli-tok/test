<?php
declare(strict_types=1);

namespace CleanCode\MeaningfulNames;

class GuessStatisticsMessage
{
    private string $number;
    private string $verb;
    private string $pluralModifier;

    public function make(string $candidate, int $count): string
    {
        $this->createPluralDependentMessageParts($count);
        return sprintf("There %s %s %s%s", $this->verb, $this->number, $candidate, $this->pluralModifier);
    }

    private function createPluralDependentMessageParts(int $count): void
    {
        if ($count === 0) {
            $this->thereAreNoLetters();
        } elseif ($count === 1) {
            $this->thereIsOneLetter();
        } else {
            $this->thereAreManyLetters($count);
        }
    }

    private function thereAreManyLetters(int $count): void
    {
        $this->number = (string)$count;
        $this->verb = "are";
        $this->pluralModifier = "s";
    }

    private function thereIsOneLetter(): void
    {
        $this->number = "1";
        $this->verb = "is";
        $this->pluralModifier = "";
    }

    private function thereAreNoLetters()
    {
        $this->number = "no";
        $this->verb = "are";
        $this->pluralModifier = "s";
    }
}