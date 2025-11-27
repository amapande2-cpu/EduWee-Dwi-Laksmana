<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Session\TokenMismatchException;

class Handler extends ExceptionHandler
{
    public function register(): void
    {
        $this->renderable(function (TokenMismatchException $e, $request) {
            return redirect()->back()
                ->with('error', 'Your session has expired. Please try again.')
                ->withInput();
        });
    }
}