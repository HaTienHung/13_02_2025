<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     * @throws AuthorizationException
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user(); // Lấy user hiện tại

        $role_name = Role::where('id', $user->role_id)->first()->name;

        if (!$user || $role_name !== $role) {
            throw new AuthorizationException(trans('message.errors.auth.unauthorized'));
        }
        return $next($request);
    }
}
