<?php


namespace App\Container;

class Container
{
    /**
     * @var array
     */
    private array $instances;

    /**
     * Container constructor.
     * @param array $instances
     */
    public function __construct(array $instances = [])
    {
        $this->instances = $instances;
    }

    /**
     * @param string $className
     * @param object|null $object
     * @return $this
     */
    public function set(string $className, ?object $object = null) : Container
    {
        if($object === null) {
            $this->instances[\get_class($object)] = $object;
        }

        $this->instances[$className] = $object;
        return $this;
    }

    /**
     * @param $className
     * @return mixed
     * @throws \Exception
     */
    public function get(string $className)
    {
        if(!$this->has($className)) {
            throw new \Exception(sprintf("Array of instances does not matter %s", $className));
        }

        return $this->instances[$className];
    }

    /**
     * @param $className
     * @return bool
     */
    public function has(string $className)
    {
        return \array_key_exists($className, $this->instances);
    }
}