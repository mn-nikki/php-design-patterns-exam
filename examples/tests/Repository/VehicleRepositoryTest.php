<?php declare(strict_types=1);

namespace Example\Test\Repository;

use Example\Adapter\JsonAdapter;
use Example\Entity\Vehicle;
use Example\ObjectFactory\Factory;
use Example\Repository\VehicleRepository;
use Example\Test\TestCase;

class VehicleRepositoryTest extends TestCase
{
    private Factory $factory;
    private JsonAdapter $adapter;
    private array $example;

    protected function setUp(): void
    {
        parent::setUp();
        $this->factory = new Factory();
        $this->adapter = new JsonAdapter($this->getData('data.json'));
        $this->example = (array) $this->adapter->getData()[1];
    }

    public function provideVehicles(): array
    {
        return [
            ['id' => 101, 'exists' => true],
            ['id' => 999005488, 'exists' => false],
        ];
    }

    /**
     * @dataProvider provideVehicles
     *
     * @param int  $id
     * @param bool $exists
     */
    public function testFindMethod(int $id, bool $exists): void
    {
        $repository = new VehicleRepository($this->adapter, $this->factory);
        $vehicle = $repository->find($id);
        if ($exists) {
            $this->assertInstanceOf(Vehicle::class, $vehicle);
            $this->assertEquals($vehicle->getId(), $id);
            $this->assertEquals($vehicle->getBuild(), $this->example['build']);
            $this->assertEquals($vehicle->getIssueDate(), $this->example['issueDate']);
            $this->assertEquals($vehicle->getMileage(), $this->example['mileage']);
        } else {
            $this->assertNull($vehicle);
        }
    }

    public function testGetMethod(): void
    {
        $repository = new VehicleRepository($this->adapter, $this->factory);
        $items = (array) $repository->get();
        $this->assertCount(\count($this->adapter->getData()), $items);
        \array_walk($items, fn ($item) => $this->assertInstanceOf(Vehicle::class, $item));
    }
}
