<?php declare(strict_types=1);


namespace App\Http;


abstract class AbstractSingleton
{
    /**
     * @var array
     */
    protected static array $instances = [];

    /**
     * @return static
     */
    public static function getInstance(): self
    {
        $subclass = static::class;
        if (!isset(self::$instances[$subclass])) {
            self::$instances[$subclass] = new static;
        }
        return self::$instances[$subclass];
    }

    protected function __construct(){}

    protected function __clone(){}

    protected function __wakeup(){}
}