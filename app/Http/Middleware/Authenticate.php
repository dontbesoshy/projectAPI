<?php

namespace App\Http\Middleware;

use App\Traits\JsonResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    use JsonResponseTrait;

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @return JsonResponse|void
     */
    protected function redirectTo(Request $request)
    {
        if (!$request->expectsJson()) {
            return $this->UNAUTHORIZED();
        }
    }
}
