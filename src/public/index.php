<?php
declare(strict_types=1);

require dirname(__DIR__, 2) . '/vendor/autoload.php';

use App\Adapter\CsvAdapter;
use App\Adapter\JsonAdapter;
use App\Repository\CarRepository;

$csvFile = new CsvAdapter(\dirname(__DIR__) . "/dataSource/data.csv");
$jsonFile = new JsonAdapter(\dirname(__DIR__) . "/dataSource/data.json");

$carRep = new CarRepository($csvFile);
$carElement = $carRep->getById(10);
$arCar = $carRep->getSlice(2, 30);

dd($arCar);
