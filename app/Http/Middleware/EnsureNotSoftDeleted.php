<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureNotSoftDeleted
{
    /**
     * If the authenticated user has been soft-deleted, log them out and redirect to login.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && $request->user()->trashed()) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors(['email' => 'Akun Anda telah dinonaktifkan.']);
        }

        return $next($request);
    }
}
