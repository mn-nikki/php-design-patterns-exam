<?php declare(strict_types=1);

namespace Example\Test\Repository;

use Example\Adapter\AdapterInterface;
use Example\Adapter\JsonAdapter;
use Example\Repository\AbstractRepository;
use Example\Test\TestCase;

class AbstractRepositoryTest extends TestCase
{
    protected function getClass(AdapterInterface $adapter): AbstractRepository
    {
        return new class($adapter) extends AbstractRepository {
            // noop
        };
    }

    public function provideIds(): array
    {
        return [
            ['id' => 191, 'exists' => true],
            ['id' => 100005488, 'exists' => false],
        ];
    }

    /**
     * @dataProvider provideIds
     *
     * @param int  $id
     * @param bool $exists
     */
    public function testFindMethod(int $id, bool $exists): void
    {
        $adapter = new JsonAdapter($this->getData('data.json'));
        $repo = $this->getClass($adapter);
        $object = $repo->find($id);
        if ($exists) {
            $this->assertIsObject($object);
            $this->assertTrue(\property_exists($object, 'mileage'));
        } else {
            $this->assertNull($object);
        }
    }

    public function provideGetNumbers(): array
    {
        return [
            ['number' => 10, 'offset' => 10],
            ['number' => null, 'offset' => 1],
        ];
    }

    /**
     * @dataProvider provideGetNumbers
     *
     * @param int|null $number
     * @param int      $offset
     */
    public function testGetMethod(?int $number, int $offset): void
    {
        $adapter = new JsonAdapter($this->getData('data.json'));
        $repo = $this->getClass($adapter);
        $data = $repo->get($number, $offset);

        $this->assertIsIterable($data);
        if ($number !== null) {
            $this->assertCount($number, $data);
        }
        $this->assertNotEquals(100, $data[0]->id);
    }

    public function testWrongCountInGet(): void
    {
        $this->expectException(\RuntimeException::class);

        $adapter = new JsonAdapter($this->getData('data.json'));
        $repo = $this->getClass($adapter);
        $offset = 10_000;
        $repo->get(1, $offset);

        $this->expectErrorMessageMatches('Maximum start number is');
    }
}
