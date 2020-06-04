<?php declare(strict_types=1);

namespace Example\Container;

use Psr\Container\NotFoundExceptionInterface;

class NotRegisteredException extends \Exception implements NotFoundExceptionInterface
{
    public function __construct($dependency, $code = 0, \Exception $previous = null)
    {
        $message = sprintf('Dependency %s is not registered', $dependency);
        parent::__construct($message, $code, $previous);
    }
}
