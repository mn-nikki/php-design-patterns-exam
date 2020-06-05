<?php declare(strict_types=1);
/**
 * Entry script.
 */

/** @noinspection PhpIncludeInspection */
require sprintf('%s/vendor/autoload.php', dirname(__DIR__, 2));

use Example\Adapter\{CsvAdapter, JsonAdapter};
use Example\Container\Container;
use Example\Controller\MainController;
use Example\Http\{Application, Request, Response};
use Example\Routes\Config;
use Example\Twig\TwigFactory;
use Twig\{Environment, Extension\DebugExtension};

$twig = TwigFactory::make([\dirname(__DIR__) . '/templates'], ['debug' => true], [new DebugExtension()]);

$container = (new Container())
    ->set(Environment::class, $twig)
    ->set(CsvAdapter::class, new CsvAdapter(\dirname(__DIR__) . '/dataSource/data.csv'))
    ->set(JsonAdapter::class, new JsonAdapter(\dirname(__DIR__) . '/dataSource/data.json'))
;

$routes = [
    '/hello-world' => static function (Request $request): Response {
        return new Response('Hello, world');
    },
    '/vehicles' => static function () use ($container): Response {
        return (new MainController($container))
            ->index();
    },
    '/vehicle' => static function (Request $request) use ($container): Response {
        return (new MainController($container))
            ->show($request);
    },
];
$routesConfig = Config::configure($routes);

$application = Application::getInstance($routesConfig);
$response = $application->handle(Request::create());
$response->send();
$application->terminate();
