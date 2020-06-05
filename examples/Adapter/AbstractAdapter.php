<?php declare(strict_types=1);

namespace Example\Adapter;

/**
 * Abstract Adapter.​​
 */
abstract class AbstractAdapter implements AdapterInterface
{
    /**
     * Array with properties which will be converted to dates.​​
     *
     * @var array|string[]
     */
    protected static array $dateFields = ['date', 'birthday', 'issueDate'];

    /**
     * Source file name.​​
     *
     * @var string
     */
    protected string $filename;

    /**
     * @param string $filename
     */
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
            throw new \RuntimeException(\sprintf('Unable to open \'%s\'', $this->filename));
        }

        return new \SplFileObject($this->filename, 'rb');
    }

    /**
     * Result values converter.​​
     *
     * @param array $data
     *
     * @return array
     */
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
