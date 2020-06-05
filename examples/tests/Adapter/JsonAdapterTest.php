<?php declare(strict_types=1);

namespace Example\Test\Adapter;

use Example\Adapter\JsonAdapter;
use Example\Test\TestCase;

class JsonAdapterTest extends TestCase
{
    public function testGetData(): void
    {
        $adapter = new JsonAdapter($this->getData('data.json'));
        $data = $adapter->getData();
        $this->assertIsIterable($data);
        $this->assertIsObject($data[0]);
    }

    public function testConvertData(): void
    {
        $data = (new JsonAdapter($this->getData('data.json')))->getData();
        $this->assertArrayHasKey(0, $data);
        $item = $data[0];
        $this->assertIsObject($item);
        $this->assertTrue(\property_exists($item, 'issueDate'));
        $this->assertInstanceOf(\DateTimeInterface::class, $item->issueDate);
    }

    public function testDecodeWrongString(): void
    {
        $this->expectException(\RuntimeException::class);

        $adapter = new JsonAdapter($this->getData('data.json'));
        $decodeString = (new \ReflectionObject($adapter))->getMethod('decodeString');
        $decodeString->setAccessible(true);

        $data = '[{"wrong": "data"}';
        $decodeString->invokeArgs($adapter, [$data]);
    }

    public function testDecodeNonArrayString(): void
    {
        $this->expectException(\RuntimeException::class);

        $adapter = new JsonAdapter($this->getData('data.json'));
        $decodeString = (new \ReflectionObject($adapter))->getMethod('decodeString');
        $decodeString->setAccessible(true);

        $data = \json_encode(['foo' => 'bar'], JSON_THROW_ON_ERROR);
        $decodeString->invokeArgs($adapter, [$data]);

        $this->expectErrorMessageMatches('Wrong data format');
    }
}
