<?php
require_once "/var/www/html/vendor/autoload.php";

use CleanCode\Employee\EmployeeFactoryImpl;
use CleanCode\Employee\EmployeeRecord;

$employeeRecords = [
    new EmployeeRecord('COMMISSIONED'),
    new EmployeeRecord('HOURLY'),
    new EmployeeRecord('SALARIED'),
];

$factory = new EmployeeFactoryImpl();

foreach ($employeeRecords as $record) {
    $employee = $factory->makeEmployee($record);

    if ($employee->isPayday()) {
        $pay = $employee->calculatePay();
        $employee->deliverPay($pay);
    }
}