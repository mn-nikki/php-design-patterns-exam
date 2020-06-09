<?php declare(strict_types=1);


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
        $this->data = $adapter->getData();
    }

    /**
     * @param int $id
     * @return object|null
     */
    public function getById(int $id): ?object
    {
        $res = array_filter((array) $this->data, function($el) use ($id) { return (int) $el->id === $id; });

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
        $count = \count((array) $this->data);
        if($count <= $offset) {
            throw new \Exception("The offset cannot be greater than the number of elements in the object.");
        }

        if($number === null) {
            $number = $count;
        }

        return array_slice((array) $this->data, $offset, $number);
    }
}