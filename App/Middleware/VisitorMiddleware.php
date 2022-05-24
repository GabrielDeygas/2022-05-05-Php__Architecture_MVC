<?php

namespace App\Middleware;


use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\JsonResponse;
use \Closure;

class VisitorMiddleware
{
    public function handle(ServerRequestInterface $request, Closure $next)
    {

        if (empty($_SESSION['USER'])) {
            return $next($request);
        }

        header('Location: /chambres');
        return null;
    }
}


