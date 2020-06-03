<?php declare(strict_types=1);

namespace Example\Adapter;

use RuntimeException;
use SplFileObject;

class CsvAdapter extends AbstractAdapter
{
    private array $headers;

    /**
     * @inheritDoc
     */
    public function getData(): iterable
    {
        $file = $this->connect();
        $this->headers = $this->makeHeaders($file);

        return $this->decodeData($file);
    }

    /**
     * @param SplFileObject $file
     * @return iterable
     */
    private function decodeData(SplFileObject $file): iterable
    {
        $file->rewind();
        $file->setFlags(SplFileObject::READ_CSV);

        $result = [];
        foreach ($file as $n => $row) {
            if ($n === 0) {
                continue;
            }

            if (\count($this->headers) === \count($row)) {
                $valueArr = \array_combine($this->headers, \array_values($row));
                $result[] = (object) $this->convertValues($valueArr);
            }
        }

        return $result;
    }

    private function makeHeaders(SplFileObject $file): array
    {
        $file->rewind();
        $file->setFlags(SplFileObject::READ_CSV);
        $data = $file->current();

        if (!\is_array($data) || ($data[0] ?? null) === null) {
            throw new RuntimeException('Wrong data format');
        }

        return \array_values($data);
    }
}
