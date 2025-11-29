<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   public function handle(Request $request, Closure $next, string $role): Response
{
    if (!auth()->check()) {
        return redirect('login');
    }

    $userRole = auth()->user()->role;
    
    // Quy định: 0=student, 1=admin, 2=teacher
    // Logic: Nếu role yêu cầu là 'admin' mà userRole != 1 -> Chặn
    
    if ($role == 'admin' && $userRole != 1) abort(403, 'Bạn không phải Admin!');
    if ($role == 'teacher' && $userRole != 2) abort(403, 'Bạn không phải Giáo viên!');
    if ($role == 'student' && $userRole != 0) abort(403, 'Bạn không phải Sinh viên!');

    return $next($request);
    }
}
