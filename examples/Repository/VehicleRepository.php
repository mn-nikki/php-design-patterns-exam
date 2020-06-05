<?php declare(strict_types=1);

namespace Example\Repository;

use Example\Adapter\AdapterInterface;
use Example\Entity\Vehicle;
use Example\ObjectFactory\Factory;
use Example\ObjectFactory\FactoryInterface;

/**
 * Repository for vehicle class.​​
 */
class VehicleRepository extends AbstractRepository
{
    protected string $className;
    private FactoryInterface $factory;

    /**
     * VehicleRepository constructor.​​
     *
     * @param AdapterInterface $adapter
     * @param FactoryInterface $factory
     */
    public function __construct(AdapterInterface $adapter, FactoryInterface $factory = null)
    {
        parent::__construct($adapter);
        $this->className = Vehicle::class;
        $this->factory = $factory ?? new Factory();
    }

    /**
     * @param int $id
     *
     * @return Vehicle|null
     */
    public function find(int $id): ?Vehicle
    {
        $result = parent::find($id);

        if ($result === null) {
            return null;
        }

        $vehicle = $this->factory->make($result, $this->className);

        return ($vehicle instanceof Vehicle) ? $vehicle : null;
    }

    /**
     * @param int|null $number
     * @param int      $offset
     *
     * @return iterable
     */
    public function get(int $number = null, int $offset = 0): iterable
    {
        return \array_map(fn (object $obj) => $this->factory->make($obj, $this->className), parent::get($number, $offset));
    }
}
