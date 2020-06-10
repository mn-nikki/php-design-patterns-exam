<?php declare(strict_types=1);


namespace App\Http;

use Symfony\Component\HttpFoundation\{Request, Response};

class Application extends AbstractSingleton
{
    /**
     * @param Request $request
     * @param array $routes
     * @return Response
     */
    public function handler(Request $request, array $routes): Response
    {
        if(!\array_key_exists($request->getPathInfo(), $routes))
        {
            return new Response('Not found, 404', Response::HTTP_NOT_FOUND);
        }

        $callback = $routes[$request->getPathInfo()];

        return $callback($request);
    }
}