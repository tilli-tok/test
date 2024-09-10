<?php
declare(strict_types=1);
namespace CleanCode\Listing14;

//Листинг 14.11. Класс ArgumentMarshaller, присоединенный к Args.java
abstract class ArgumentMarshaler
{
    protected bool $booleanValue = false;
    private string $stringValue;
    private int $integerValue;

    //delete setBoolean
    /**
    public function setBoolean(bool $value): void
    {
        $this->booleanValue = $value;
    }*/

    public function getBoolean($arg): bool
    {
        return $this->booleanValue;
    }

    public function setString(String $s): void
    {
        $this->stringValue = $s;
    }
    public function getString(): string
    {
        return $this->stringValue == null ? "" : $this->stringValue;
    }
    public function setInteger(int $i): void
    {
        $this->integerValue = $i;
     }
    public function getInteger(): int
    {
        return $this->integerValue;
    }
    public abstract function set(String $s): void;
    //public function get(): ?object {
    //    return null;
    //}
    public abstract function get();
}