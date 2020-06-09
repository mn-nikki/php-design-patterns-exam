<?php


namespace App\Repository;

use App\Adapter\AdapterInterface;

abstract class AbstractRepository
{
    /**
     * @var AdapterInterface
     */
    protected AdapterInterface $adapter;
    /**
     * @var iterable
     */
    protected iterable $data;

    /**
     * AbstractRepository constructor.
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
        $this->data = (array) $adapter->getData();
    }

    /**
     * @param int $id
     * @return object|null
     */
    public function getById(int $id): ?object
    {
        $res = array_filter($this->data, function($el) use ($id) { return (int) $el->id === $id; });

        if(empty($res)) return null;

        return \array_values($res)[0] ?? null;
    }

    /**
     * @param int|null $number
     * @param int $offset
     * @return iterable
     * @throws \Exception
     */
    public function getSlice(int $number = null, int $offset = 0): iterable
    {
        if(\count($this->data) <= $offset) {
            throw new \Exception("The offset cannot be greater than the number of elements in the object.");
        }

        if($number === null) {
            $number = \count($this->data);
        }

        return array_slice($this->data, $offset, $number);
    }
}