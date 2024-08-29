<?php
require_once __DIR__ ."./../../vendor/autoload.php";

use CleanCode\Demetra\Address;

$address = new Address(
    street: 'Красногорский бульвар',
    streetExtra: '',
    city: 'Красногорск',
    state: 'Красногорский район',
    zip: '123456'
);

$street = $address->getStreet();
$streetExtra = $address->getStreetExtra();
$city = $address->getCity();
$state = $address->getState();
$zip = $address->getZip();

echo "Адресс: $state, $city, $street $streetExtra, $zip\n";