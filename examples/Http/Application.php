<?php declare(strict_types=1);

namespace Example\Http;

use Example\Routes\Config;

/**
 * Application.
 */
class Application
{
    private static ?Application $instance = null;
    private ?Config $routesConfig = null;

    /**
     * @param Config|null $routesConfig
     *
     * @return Application
     */
    public static function getInstance(?Config $routesConfig = null): Application
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        if ($routesConfig !== null) {
            static::$instance->routesConfig = $routesConfig;
        }

        return self::$instance;
    }

    public function terminate(): void
    {
        self::$instance = null;
        $this->routesConfig = null;
    }

    /**
     * @param Request     $request
     * @param Config|null $routesConfig
     *
     * @return Response
     */
    public function handle(Request $request, ?Config $routesConfig = null): Response
    {
        if ($routesConfig !== null) {
            $this->routesConfig = $routesConfig;
        }
        if ($this->routesConfig === null) {
            throw new \RuntimeException('Routes config is not defined');
        }

        if (!$this->routesConfig->offsetExists($request->getPathInfo())) {
            return new Response('Page not found', Response::HTTP_NOT_FOUND);
        }

        $callback = $this->routesConfig->offsetGet($request->getPathInfo());

        return $callback($request);
    }

    /**
     * Application constructor.
     * Prevent from creating multiple instances.
     */
    private function __construct()
    {
    }

    /**
     * Forbidden.
     */
    private function __clone()
    {
    }

    /**
     * Forbidden.
     */
    private function __wakeup()
    {
    }
}
