<?php declare(strict_types=1);

namespace Example\Repository;

use Example\Adapter\AdapterInterface;

/**
 * Abstract Repository.
 */
abstract class AbstractRepository
{
    protected AdapterInterface $adapter;
    protected array $data;

    /**
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
        $this->data = $adapter->getData();
    }

    /**
     * Lookup object by ID.
     *
     * @param int $id
     *
     * @return object|null
     */
    public function find(int $id): ?object
    {
        $result = \array_filter($this->data, fn (object $object) => \property_exists($object, 'id') && (int) $object->id === $id);

        if (empty($result)) {
            return null;
        }

        return \array_values($result)[0] ?? null;
    }

    /**
     * Get some number of data with offset.
     *
     * @param int|null $number
     * @param int      $offset
     *
     * @return iterable
     */
    public function get(int $number = null, int $offset = 0): iterable
    {
        $count = \count($this->data);
        if ($offset >= \count($this->data)) {
            throw new \RuntimeException(\sprintf('Maximum start number is %d, %d given', ($count - 1), $offset));
        }

        if ($number === null) {
            $number = $count;
        }

        return \array_slice($this->data, $offset, $number);
    }
}
