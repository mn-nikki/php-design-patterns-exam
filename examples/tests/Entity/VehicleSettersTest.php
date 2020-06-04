<?php declare(strict_types=1);

namespace Example\Test\Entity;

use Example\Entity\Vehicle;
use Example\Test\TestCase;

class VehicleSettersTest extends TestCase
{
    public function provideMethods(): array
    {
        return [
            ['setBuild', 'Mitsubishi'],
            ['setIssueDate', \date_create()],
            ['setMileage', 7855647],
        ];
    }

    /**
     * @dataProvider provideMethods
     *
     * @param string $method
     * @param $value
     */
    public function testVehicleSetter(string $method, $value): void
    {
        $this->assertInstanceOf(Vehicle::class, (new Vehicle())->{$method}($value));
    }
}
