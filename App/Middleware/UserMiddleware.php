<?php

namespace App\Middleware;


use App\Model\User;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\JsonResponse;
use \Closure;

class UserMiddleware
{
    public function handle(ServerRequestInterface $request, Closure $next)
    {

        if (isset($_SESSION['USER'])) {
            return $next($request);
        } else {
            header('Location: /');
        }
        return null;
    }
}
