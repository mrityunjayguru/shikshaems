<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CheckRole {
    /**
     * Handle an incoming request.
     * Usage in routes: ->middleware('Role:admin') or ->middleware('Role:admin,teacher')
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @param string ...$roles  Allowed roles (optional)
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next, string ...$roles) {
        $school_database_name = Session::get('school_database_name');
        if ($school_database_name) {
            DB::setDefaultConnection('school');
            Config::set('database.connections.school.database', $school_database_name);
            DB::purge('school');
            DB::connection('school')->reconnect();
            DB::setDefaultConnection('school');
        } else {
            DB::purge('school');
            DB::connection('mysql')->reconnect();
            DB::setDefaultConnection('mysql');
        }

        if (!Auth::check()) {
            return response()->view('auth.login');
        }

        // If specific roles are required, verify the user has one of them
        if (!empty($roles)) {
            $user = Auth::user();
            $userRoles = $user->getRoleNames()->toArray();
            $hasRole = !empty(array_intersect($roles, $userRoles));

            if (!$hasRole) {
                abort(403, 'Unauthorized. Required role: ' . implode(' or ', $roles));
            }
        }

        return $next($request);
    }
}
