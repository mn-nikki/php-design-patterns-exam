<?php declare(strict_types=1);
/**
 * 04.06.2020.
 */

namespace Example\Http;

class ParameterBag implements \IteratorAggregate, \Countable
{
    private array $parameters;

    public function __construct(array $parameters = [])
    {
        $this->parameters = $parameters;
    }

    public function all(): array
    {
        return $this->parameters;
    }

    public function add(array $parameters = []): self
    {
        $this->parameters = \array_replace($this->parameters, $parameters);

        return $this;
    }

    public function get(string $key, $default = null)
    {
        return \array_key_exists($key, $this->parameters) ? $this->parameters[$key] : $default;
    }

    public function set(string $key, $value): self
    {
        $this->parameters[$key] = $value;

        return $this;
    }

    public function has(string $key): bool
    {
        return \array_key_exists($key, $this->parameters);
    }

    public function remove(string $key): self
    {
        unset($this->parameters[$key]);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->parameters);
    }

    public function count()
    {
        return \count($this->parameters);
    }
}
