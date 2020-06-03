<?php declare(strict_types=1);

namespace Example\Adapter;

use JsonException;
use RuntimeException;

class JsonAdapter extends AbstractAdapter
{
    /**
     * @inheritDoc
     */
    public function getData(): iterable
    {
        $file = $this->connect();

        return $this->decodeString($file->fread($file->getSize()));
    }

    protected function decodeString(string $data): iterable
    {
        try {
            $result = \json_decode($data, false, 8, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new RuntimeException($e->getMessage(), (int) $e->getCode(), $e);
        }

        if (!\is_iterable($result)) {
            throw new \RuntimeException('Wrong data format');
        }
        $converted = [];
        foreach ($result as $item) {
            $converted[] = (object) $this->convertValues((array) $item);
        }

        return $converted;
    }
}
