<?php
declare(strict_types=1);

require dirname(__DIR__, 2) . '/vendor/autoload.php';

use App\Adapter\{CsvAdapter, JsonAdapter};
use Symfony\Component\HttpFoundation\{Request, Response};
use App\Http\Application;
use App\Container\Container;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use App\Controller\CarController;

$csvFile = new CsvAdapter(\dirname(__DIR__) . "/dataSource/data.csv");
$jsonFile = new JsonAdapter(\dirname(__DIR__) . "/dataSource/data.json");

$container = (new Container())
    ->set(CsvAdapter::class, $csvFile)
    ->set(JsonAdapter::class, $jsonFile)
    ->set(Environment::class, new Environment(new FilesystemLoader(\dirname(__DIR__) . '/templates')));

$routes = [
    '/' => static function (Request $request): Response {
        return new Response('Its main page');
    },
    '/cars' => static function (Request $request) use ($container): Response {
        return (new CarController($container))->index();
    }
];

$application = Application::getInstance();

$response = $application->handler(Request::createFromGlobals(), $routes);
$response->send();