<?php declare(strict_types=1);

namespace Example\Adapter;

/**
 * Interface for data fetcher.
 */
interface AdapterInterface
{
    /**
     * Read a file.
     *
     * @return mixed|\SplFileObject
     */
    public function connect();

    /**
     * Return whole data.
     *
     * @return iterable|object[]
     */
    public function getData(): iterable;
}
