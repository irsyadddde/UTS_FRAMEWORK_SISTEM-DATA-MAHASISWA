<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Redirect based on role
        if ($user->role == 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role == 'dosen') {
            return redirect()->route('dosen.dashboard');
        } elseif ($user->role == 'mahasiswa') {
            return redirect()->route('mahasiswa.dashboard');
        }

        abort(403, 'Unauthorized access.');
    }
}