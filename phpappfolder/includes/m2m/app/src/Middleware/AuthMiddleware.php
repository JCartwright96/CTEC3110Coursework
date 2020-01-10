<?php

namespace M2m\Middleware;

class AuthMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        if (!$this->container->auth->getStatus()) {
            return $response->withRedirect($this->container->router->pathFor('login'));
        }

        $response = $next($request, $response);
        return $response;
    }
}