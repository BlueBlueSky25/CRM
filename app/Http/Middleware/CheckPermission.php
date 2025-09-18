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
        
        if ($request->has('role')) {
                if ($request->role === 'superadmin' && $user->role->role_name !== 'superadmin') {
                    return redirect()->back()->with('error', 'Hanya superadmin yang bisa assign role superadmin.');
        }
    }

        // Ambil menu sesuai route
        $menu = $user->role->menus()->where('route', $routeName)->first();

        // Super admin bisa akses semua
        if ($user->role->role_name === 'superadmin') {
            // Share menu_id ke view
            view()->share('currentMenuId', $menu?->menu_id);
            return $next($request);
        }

        // Cek permission view
        if (!$menu || !$menu->pivot->can_view) {
        return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }

        // Share menu_id ke view supaya Blade bisa pakai untuk cek edit/delete/create
        view()->share('currentMenuId', $menu->menu_id);

        return $next($request);
    }
}
