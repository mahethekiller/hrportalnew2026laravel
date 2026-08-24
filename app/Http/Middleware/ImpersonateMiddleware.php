<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ImpersonateMiddleware
{
    /**
     * Handle an incoming request during user impersonation.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session()->has('impersonated_by')) {
            // High-consequence financial/payroll routes locked during impersonation
            $lockedRoutes = [
                'payroll.store',
                'payroll.destroy',
                'salary-history.store',
                'settings.navigation.destroy'
            ];

            $currentRoute = $request->route()?->getName();

            if (in_array($currentRoute, $lockedRoutes)) {
                return redirect()->back()->with('error', 'Security Policy: Financial disbursements, payroll locks, and system deletions are prohibited while viewing as another user.');
            }
        }

        return $next($request);
    }
}
