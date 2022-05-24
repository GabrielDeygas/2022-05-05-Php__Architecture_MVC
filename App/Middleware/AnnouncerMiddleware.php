<?php

namespace App\Middleware;


use App\Model\User;
use Psr\Http\Message\ServerRequestInterface;
use \Closure;

class AnnouncerMiddleware
{
    public function handle(ServerRequestInterface $request, Closure $next)
    {

        if (isset($_SESSION['USER']) && $_SESSION['USER']->user_type === User::ANNOUNCER) {
            return $next($request);
        } else {
            header('Location: /chambres');
        }
        return null;
    }
}
