<?php declare(strict_types=1);


namespace App\Repository;


use App\Adapter\AdapterInterface,
    App\Factory\Factory;

class CarRepository extends AbstractRepository
{
    /**
     * @var Factory|null
     */
    private ?Factory $factory = null;

    /**
     * @var string|null
     */
    private ?string $className = null;

    /**
     * CarRepository constructor.
     * @param AdapterInterface $adapter
     * @param string|null $className
     */
    public function __construct(AdapterInterface $adapter, ?string $className = null)
    {
        parent::__construct($adapter);

        if($className !== null) {
            $this->className = $className;
            $this->factory = new Factory();
        }
    }

    /**
     * @param int $id
     * @return object|null
     * @throws \ReflectionException
     */
    public function getById(int $id): ?object
    {
        $data = parent::getById($id);

        if($data !== null && $this->className !== null) {
            $data = $this->factory->build($data, $this->className);
        }

        return $data;
    }

    /**
     * @param int|null $number
     * @param int $offset
     * @return iterable
     * @throws \Exception
     */
    public function getSlice(int $number = null, int $offset = 0): iterable
    {
        $data = parent::getSlice($number, $offset);

        if($this->className !== null) {
            $data = \array_map(fn (object $el) => $this->factory->build($el, $this->className), (array) parent::getSlice($number, $offset));
        }

        return $data;
    }
}