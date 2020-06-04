<?php declare(strict_types=1);

namespace Example\Routes;

/**
 * Simple router config.
 */
class Config implements \ArrayAccess, \IteratorAggregate
{
    /**
     * @var array|callable[]
     */
    protected array $routes;

    /**
     * @param array $routes
     */
    public function __construct(array $routes = [])
    {
        $this->routes = $routes;
    }

    /**
     * @param array $configuration
     *
     * @return static
     */
    public static function configure(array $configuration): self
    {
        $instance = new static();

        foreach ($configuration as $key => $value) {
            if (\is_callable($value)) {
                $instance->offsetSet($key, $value);
            }
        }

        return $instance;
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->routes);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetExists($offset): bool
    {
        return \array_key_exists($offset, $this->routes);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet($offset)
    {
        return $this->routes[$offset] ?? null;
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet($offset, $value): self
    {
        $this->routes[$offset] = $value;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset($offset): self
    {
        unset($this->routes[$offset]);

        return $this;
    }
}
