<?php declare(strict_types=1);

namespace Example\Test\Adapter;

use Example\Adapter\CsvAdapter;
use Example\Test\TestCase;

class CsvAdapterTest extends TestCase
{
    public function tearDown(): void
    {
        parent::tearDown();
        $files = \glob(\sprintf('%s/test-*', \sys_get_temp_dir()));
        if (\is_array($files)) {
            foreach ($files as $file) {
                @\unlink($file);
            }
        }
    }

    public function testGetDataMethod(): void
    {
        $filename = $this->getData('data.csv');
        $adapter = new CsvAdapter($filename);
        $data = $adapter->getData();
        $count = \count(file($filename)) - 1;

        $this->assertIsIterable($data);
        $this->assertEquals(\count($data), $count);

        $headers = \fgetcsv(\fopen($filename, 'rb'));
        $this->assertEquals(\array_values($headers), \array_keys((array) $data[0]));
        $this->assertNotEquals(\array_values($headers), \array_values((array) $data[0]));

        $this->assertIsObject($data[0]);
    }

    public function testConverter(): void
    {
        $data = (new CsvAdapter($this->getData('data.csv')))->getData();
        $this->assertNotEmpty($data[0]);
        $item = $data[0];

        $this->assertIsObject($item);
        $this->assertTrue(\property_exists($item, 'issueDate'));
        $this->assertInstanceOf(\DateTimeInterface::class, $item->issueDate);
    }

    public function testMakeFileHeaders(): void
    {
        $this->expectException(\RuntimeException::class);

        $file = \tempnam(\sys_get_temp_dir(), 'test-');
        $adapter = new CsvAdapter($file);
        $makeHeaders = (new \ReflectionObject($adapter))->getMethod('makeHeaders');
        $makeHeaders->setAccessible(true);
        $makeHeaders->invokeArgs($adapter, [new \SplFileObject($file, 'rb')]);

        $this->expectErrorMessageMatches('Wrong data format');
    }
}
