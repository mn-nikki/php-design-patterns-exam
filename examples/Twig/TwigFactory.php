<?php declare(strict_types=1);

namespace Example\Twig;

use Twig\Environment;
use Twig\Extension\ExtensionInterface;
use Twig\Loader\FilesystemLoader;
use Twig\Loader\LoaderInterface;

/**
 * Factory for Twig environment.
 */
class TwigFactory
{
    /**
     * @param array                      $paths      Paths to templates
     * @param array                      $options    Environment options
     * @param array|ExtensionInterface[] $extensions
     * @param LoaderInterface|null       $loader
     *
     * @return Environment
     */
    public static function make(array $paths = [], array $options = [], array $extensions = [], ?LoaderInterface $loader = null): Environment
    {
        if ($loader === null) {
            $loader = new FilesystemLoader($paths);
        }

        $twig = new Environment($loader, $options);
        foreach ($extensions as $extension) {
            if ($extension instanceof ExtensionInterface) {
                $twig->addExtension($extension);
            }
        }

        return $twig;
    }
}
