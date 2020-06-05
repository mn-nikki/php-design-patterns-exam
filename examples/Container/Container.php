<?php declare(strict_types=1);

namespace Example\Container;

use Psr\Container\ContainerInterface;

/**
 * Simple container implementation.
 */
class Container implements ContainerInterface, \ArrayAccess
{
    protected array $instances;

    /**
     * @param array $instances
     */
    public function __construct(array $instances = [])
    {
        $this->instances = $instances;
    }

    /**
     * @param string|object $abstract ID of container instance
     * @param object|null   $concrete
     *
     * @return $this
     */
    public function set($abstract, object $concrete = null): self
    {
        if ($concrete === null) {
            $this->instances[\get_class($concrete)] = $concrete;

            return $this;
        }

        $this->instances[$abstract] = $concrete;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function get($id)
    {
        if (!$this->has($id)) {
            throw new NotRegisteredException($id);
        }

        return $this->instances[$id];
    }

    /**
     * @inheritDoc
     */
    public function has($id): bool
    {
        return $this->offsetExists($id);
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset): bool
    {
        return \array_key_exists($offset, $this->instances);
    }

    /**
     * @inheritDoc
     *
     * @throws NotRegisteredException
     */
    public function offsetGet($offset)
    {
        if (($this->instances[$offset] ?? null) === null) {
            throw new NotRegisteredException($offset);
        }

        return $this->instances[$offset];
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value): self
    {
        if ($offset === null) {
            $this->set($value);
        } else {
            $this->set($offset, $value);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset): self
    {
        unset($this->instances[$offset]);

        return $this;
    }
}
