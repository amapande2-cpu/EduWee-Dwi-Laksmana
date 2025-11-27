<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    protected function redirectTo(Request $request): ?string
    {
        if (!$request->expectsJson()) {
            // Check URL to determine which login page
            if (str_starts_with($request->path(), 'teacher')) {
                return route('teacher.login');
            }
            
            return route('student.login');
        }
        
        return null;
    }
}