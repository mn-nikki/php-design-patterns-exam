<?php declare(strict_types=1);
/**
 * 03.06.2020.
 */

namespace Example\ObjectFactory;

/**
 * Factory Interface.
 */
interface FactoryInterface
{
    /**
     * Tries to make object of any class.
     *
     * @param $data
     * @param string|null $className
     *
     * @return object|array
     */
    public function make($data, ?string $className = null): object;
}
