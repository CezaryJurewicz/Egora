<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof \Illuminate\Session\TokenMismatchException) {
            return redirect()->route('login');
        }
        
        if ($exception instanceof \Illuminate\Auth\Access\AuthorizationException) {
            return redirect_to_egora_home();
        }
        
        if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            if (($exception->getModel() == \App\Idea::class) && $request->has('notification_id')) {
                return redirect()->route('ideas.popularity_indexes')->with(['message' => 'This idea has lost all support and no longer exists.']); 
            }
        }
        return parent::render($request, $exception);
    }
}
