<?php declare(strict_types=1);

namespace Example\Container;

use Psr\Container\ContainerExceptionInterface;

class NotInstantiableException extends \Exception implements ContainerExceptionInterface
{
    public function __construct($dependency, $code = 0, \Exception $previous = null)
    {
        $message = sprintf('Dependency %s is not instantiable', $dependency);
        parent::__construct($message, $code, $previous);
    }
}
