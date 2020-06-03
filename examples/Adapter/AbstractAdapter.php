<?php declare(strict_types=1);

namespace Example\Adapter;

use RuntimeException;
use SplFileObject;

abstract class AbstractAdapter implements AdapterInterface
{
    protected static array $dateFields = ['date', 'birthday', 'issueDate'];

    protected string $filename;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    /**
     * @inheritDoc
     */
    public function connect()
    {
        if (!\is_file($this->filename) || !\is_readable($this->filename)) {
            throw new RuntimeException(\sprintf('Unable to open \'%s\'', $this->filename));
        }

        return new SplFileObject($this->filename, 'rb');
    }

    protected function convertValues(array $data): array
    {
        $result = [];
        foreach ($data as $key => $value) {
            if (!empty($value) && \in_array($key, self::$dateFields, false)) {
                $value = \date_create_from_format('d.m.Y', $value);
            }

            if (empty($value)) {
                $value = null;
            }

            $result[$key] = $value;
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    abstract public function getData(): iterable;
}
