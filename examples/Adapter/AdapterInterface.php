<?php declare(strict_types=1);

namespace Example\Adapter;

use SplFileObject;

/**
 * Interface for data fetcher.
 */
interface AdapterInterface
{
    /**
     * @return mixed|resource|SplFileObject
     */
    public function connect();

    /**
     * @return iterable|object[]
     */
    public function getData(): iterable;
}
