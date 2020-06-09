<?php declare(strict_types=1);


namespace App\Adapter;


interface AdapterInterface
{
    /**
     * @return \SplFileObject
     */
    public function connect() : \SplFileObject;

    /**
     * @return iterable
     */
    public function getData(): iterable;
}