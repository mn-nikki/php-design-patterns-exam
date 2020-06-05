<?php declare(strict_types=1);

namespace Example\Test;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function getData(?string $append = null): string
    {
        $dir = sprintf('%s/_data', __DIR__);

        if ($append) {
            $target = \sprintf('%s/%s', $dir, \ltrim($append, '/'));
            if (!\is_readable($target)) {
                throw new \RuntimeException(\sprintf('\'%s\' is not readable', $target));
            }

            return $target;
        }

        return $dir;
    }
}
