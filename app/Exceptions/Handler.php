<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;

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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    protected function invalidJson($request, ValidationException $exception)
    {
        $messages = $exception->errors();
        $output_array = [];
        foreach($messages as $message){
            array_push($output_array,$message[0]);
        }
        return response()->json([
                'status' => false,
                'data' => "",
                'error' => implode(", ",$output_array),
                'message' => "Exception Error"
                ], 200);
    }
}
