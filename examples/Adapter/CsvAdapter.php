<?php declare(strict_types=1);

namespace Example\Adapter;

/**
 * Adapter for csv-files.
 */
class CsvAdapter extends AbstractAdapter
{
    /**
     * Property names (from first string).
     *
     * @var array
     */
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
     * @param \SplFileObject $file
     *
     * @return iterable
     */
    private function decodeData(\SplFileObject $file): iterable
    {
        $file->rewind();
        $file->setFlags(\SplFileObject::READ_CSV);

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

    /**
     * Makes property names array from first csv-string.
     *
     * @param \SplFileObject $file
     *
     * @return array
     */
    private function makeHeaders(\SplFileObject $file): array
    {
        $file->rewind();
        $file->setFlags(\SplFileObject::READ_CSV);
        $data = $file->current();

        if (!\is_array($data) || ($data[0] ?? null) === null) {
            throw new \RuntimeException('Wrong data format');
        }

        return \array_values($data);
    }
}
