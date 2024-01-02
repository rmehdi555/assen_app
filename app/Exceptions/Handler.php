<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {

        $this->renderable(function (ThrottleRequestsException $e) {
            return response()->json([
                'status' => 404,
                'errors' => $e->getCode(),
                'message' => __('messages.there_were_too_many_requests_Please_try_again_in_a_few_moments'),
            ], 404);
        });
        $this->renderable(function (ValidationException $e) {
            return response()->json([
                'status' => 422,
                'errors' => $e->errors(),
                'message' => $e->getMessage(),
            ], 404);
        });
    }
}
