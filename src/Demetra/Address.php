<?php
declare(strict_types=1);
namespace CleanCode\Demetra;


class Address{
    private string $street;
    private ?string $streetExtra;
    private string $city;
    private string $state;
    private string $zip;
    public function __construct(string $street, string $streetExtra, string $city, string $state, string $zip){
        $this->street = $street;
        $this->streetExtra = $streetExtra;
        $this->city = $city;
        $this->state = $state;
        $this->zip = $zip;
    }
    public function getStreet(): string{
        return $this->street;
    }
    public function getStreetExtra(): ?string{
        return $this->streetExtra;
    }
    public function getCity(): string{
        return $this->city;
    }
    public function getState(): string{
        return $this->state;
    }
    public function getZip(): string{
        return $this->zip;
    }
}