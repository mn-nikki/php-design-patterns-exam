<?php declare(strict_types=1);

namespace Example\ObjectFactory;

/**
 * Factory for object of any class creation.
 */
class Factory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function make($data, ?string $className = null): object
    {
        if (!\is_array($data) && !$this->checkObject($data)) {
            throw new \RuntimeException('Neither object nor associative array given');
        }

        if ($className !== null) {
            return $this->createClassObject((array) $data, $className);
        }

        return (object) $data;
    }

    /**
     * Check if object has valid structure: only `{"key" => "value"}` allowed.
     *
     * @param $data
     *
     * @return bool
     */
    private function checkObject($data): bool
    {
        if (!\is_object($data)) {
            return false;
        }

        $arr = (array) $data;
        $filtered = \array_filter($arr, fn ($key) => \is_string($key), ARRAY_FILTER_USE_KEY);

        return $filtered === $arr;
    }

    /**
     * Makes an instance of given class.
     *
     * @param array  $data
     * @param string $className
     *
     * @return object
     */
    private function createClassObject(array $data, string $className): object
    {
        try {
            $reflection = new \ReflectionClass($className);
        } catch (\ReflectionException $e) {
            throw new \RuntimeException($e->getMessage(), (int) $e->getCode(), $e);
        }
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PRIVATE | \ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PUBLIC);
        $class = $reflection->newInstanceWithoutConstructor();
        foreach ($properties as $property) {
            $property->setValue($class, $this->getPropertyValue($property, $data));
        }

        return $class;
    }

    /**
     * Get value of property from array.
     *
     * @param \ReflectionProperty $property
     * @param array               $data
     *
     * @return mixed|null
     */
    private function getPropertyValue(\ReflectionProperty $property, array $data)
    {
        $name = $property->getName();
        $property->setAccessible(true);

        return $data[$name] ?? null;
    }
}
