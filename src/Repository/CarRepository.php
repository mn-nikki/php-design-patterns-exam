<?php


namespace App\Repository;


use App\Adapter\AdapterInterface;

class CarRepository extends AbstractRepository
{
    public function __construct(AdapterInterface $adapter)
    {
        parent::__construct($adapter);
    }
}