<?php


namespace App\Factory;


class Factory
{
    /**
     * @param object $data
     * @param string $className
     * @return object
     * @throws \ReflectionException
     */
    public function build(object $data, string $className) : object
    {
        try {
            $reflectionClass = new \ReflectionClass($className);
        } catch (\ReflectionException $exception) {
            throw new \ReflectionException($exception->getMessage());
        }

        $instance = $reflectionClass->newInstanceWithoutConstructor();

        foreach ((array) $data as $name => $value)
        {
            if(\property_exists($className, $name))
            {
                $prop = $reflectionClass->getProperty($name);
                $prop->setAccessible(true);
                $prop->setValue($instance, $value);
            }
        }

        return $instance;
    }
}