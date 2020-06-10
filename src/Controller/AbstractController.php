<?php


namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use App\Container\Container;
use Twig\Environment;

class AbstractController
{
    /**
     * @var Container
     */
    private Container $container;

    /**
     * AbstractController constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $template
     * @param array $vars
     * @return Response
     */
    public function render(string $template, array $vars) :Response
    {
        try {
            $twig = $this->container->get(Environment::class);
            $content = $twig->render($template, $vars);

            return new Response($content, Response::HTTP_OK);
        } catch (\Exception $e) {
            return new Response($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}