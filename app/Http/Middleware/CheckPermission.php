<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $routeName = $request->route()->getName();
        
        // Super admin bisa akses semua
        if ($user->role->role_name === 'superadmin') {
            return $next($request);
        }

        // Cek permission berdasarkan route name
        $canAccess = $user->role->menus()
            ->where('route', $routeName)
            ->where('can_view', true)
            ->exists();

        if (!$canAccess) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}