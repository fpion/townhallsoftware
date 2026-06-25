<?php

class TownHall
{
    private string $name;
    private Address $address;
    private TownHallCode $code;

    public function __construct(string $name, Address $address, TownHallCode $code)
    {
        $this->name = $name;
        $this->address = $address;
        $this->code = $code;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function getCode()
    {
        return $this->code;
    }
    }



class Address
{
    private string $street;
    private string $city;
    private string $postalCode;

    public function __construct(string $street, string $city, string $postalCode)
    {
        $this->street = $street;
        $this->city = $city;
        $this->postalCode = $postalCode;
    }

    public function getStreet()
    {
        return $this->street;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function getPostalCode()
    {
        return $this->postalCode;
    }
}

class TownHallCode
{
    private string $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public function getCode()
    {
        return $this->code;
    }
}