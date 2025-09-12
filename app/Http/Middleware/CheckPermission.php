<?php
// app/Http/Middleware/CheckPermission.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission = null): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Super admin has all permissions
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Get current route name
        $routeName = $request->route()->getName();
        
        // Use provided permission or route name
        $permissionToCheck = $permission ?? $routeName;
        
        // Route yang tidak memerlukan permission check
        $publicRoutes = [
            'profile',
            'profile.edit', 
            'profile.update',
            'logout'
        ];

        // Skip permission check untuk route public
        if (in_array($routeName, $publicRoutes)) {
            return $next($request);
        }

        // Check if user has the required permission
        if (!$user->hasPermission($permissionToCheck)) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin untuk mengakses fitur ini.'
                ], 403);
            }
            
            // Redirect to first accessible route instead of showing 403
            $firstAccessibleRoute = $user->getFirstAccessibleRoute();
            if ($firstAccessibleRoute) {
                return redirect()->route($firstAccessibleRoute)
                    ->with('error', 'Anda tidak memiliki izin untuk mengakses halaman tersebut.');
            }
            
            // If no accessible route, logout
            auth()->logout();
            return redirect()->route('login')
                ->with('error', 'Akun Anda tidak memiliki akses ke sistem. Silakan hubungi administrator.');
        }

        return $next($request);
    }
}