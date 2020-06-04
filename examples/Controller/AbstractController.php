<?php declare(strict_types=1);

namespace Example\Controller;

use Example\Http\Response;
use Psr\Container\ContainerInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

abstract class AbstractController
{
    protected ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $template
     * @param array  $params
     *
     * @return string
     *
     * @throws LoaderError|RuntimeError|SyntaxError
     */
    public function renderString(string $template, array $params = []): string
    {
        /** @var Environment $twig */
        $twig = $this->container->get(Environment::class);

        return $twig->render($template, $params);
    }

    /**
     * @param string $template
     * @param array  $params
     *
     * @return Response
     */
    public function render(string $template, array $params = []): Response
    {
        try {
            $content = $this->renderString($template, $params);

            return new Response($content, Response::HTTP_OK);
        } catch (LoaderError | RuntimeError | SyntaxError $e) {
            return new Response($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
