<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Middleware\ControllerMiddlewareOptions;

class EnsureTokenIsValid
{
    protected $middleware = [];

    public function middleware($middleware, array $options = [])
    {
        foreach ((array) $middleware as $m) {
            $this->middleware[] = [
                'middleware' => $m,
                'options' => &$options,
            ];
        }

        return new ControllerMiddlewareOptions($options);
    }

   
    public function getMiddleware()
    {
        return $this->middleware;
    }

    
    public function callAction($method, $parameters)
    {
        return $this->{$method}(...array_values($parameters));
    }

    
    public function __call($method, $parameters)
    {
        throw new BadMethodCallException(sprintf(
            'Method %s::%s does not exist.',
            static::class,
            $method
        ));
    }
}
