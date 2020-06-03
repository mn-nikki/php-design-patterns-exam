<?php declare(strict_types=1);
/**
 * 03.06.2020.
 */

namespace Example\Test\Adapter;

use Example\Adapter\AbstractAdapter;
use Example\Adapter\AdapterInterface;
use Example\Test\TestCase;

class AbstractAdapterTest extends TestCase
{
    protected function getClass(string $filename): AdapterInterface
    {
        return new class($filename) extends AbstractAdapter {
            public function getData(): iterable
            {
                return [];
            }
        };
    }

    public function testNotAFile(): void
    {
        $this->expectException(\RuntimeException::class);

        $file = '/file/does/not/exists.csv';
        $adapter = $this->getClass($file);
        $adapter->connect();

        $this->expectErrorMessageMatches('Unable to open');
    }
}
