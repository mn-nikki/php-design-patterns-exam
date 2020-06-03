<?php declare(strict_types=1);

namespace Example\Test\Factory;

use Example\Adapter\JsonAdapter;
use Example\Entity\Vehicle;
use Example\ObjectFactory\Factory;
use Example\Test\TestCase;

class FactoryTest extends TestCase
{
    protected object $itemObject;

    protected function setUp(): void
    {
        parent::setUp();
        $data = (new JsonAdapter($this->getData('data.json')))->getData();
        $this->itemObject = $data[0];
    }

    public function testMakeObject(): void
    {
        $factory = new Factory();
        $data = $factory->make($this->itemObject);
        $this->assertSame($this->itemObject, $data);
    }

    public function testMakeObjectWithClass(): void
    {
        /** @var Vehicle $data */
        $data = (new Factory())->make($this->itemObject, Vehicle::class);
        $this->assertInstanceOf(Vehicle::class, $data);
        $this->checkValues($data, (array) $this->itemObject);
    }

    protected function checkValues(Vehicle $vehicle, array $data): void
    {
        $properties = ['id', 'build', 'issueDate', 'mileage'];
        foreach ($properties as $property) {
            $getter = \sprintf('get%s', \ucfirst($property));
            $this->assertTrue(\method_exists($vehicle, $getter));
            $this->assertEquals($data[$property], $vehicle->{$getter}());
        }
    }

    public function testWrongDataObjectInMake(): void
    {
        $this->expectException(\RuntimeException::class);
        $data = (object) ['foo', 'bar', 'baz'];
        (new Factory())->make($data);
        $this->expectErrorMessage('Neither object nor associative array given');
    }

    public function testNotObjectInMake(): void
    {
        $this->expectException(\RuntimeException::class);
        (new Factory())->make('STRING');
        $this->expectErrorMessage('Neither object nor associative array given');
    }

    public function testNotExistingClass(): void
    {
        $this->expectException(\RuntimeException::class);
        $factory = new Factory();
        $createClassObject = (new \ReflectionObject($factory))->getMethod('createClassObject');
        $createClassObject->setAccessible(true);
        $createClassObject->invokeArgs($factory, [['foo' => 'bar'], 'Class\\That\\Not\\Exists']);
    }
}
