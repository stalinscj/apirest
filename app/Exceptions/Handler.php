<?php

namespace App\Exceptions;

use Exception;
use App\Traits\ApiResponser;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    use ApiResponser;
    
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
        

        if ($exception instanceof ValidationException) {
            if ($this->isFrontEnd($request)){
                return $request->ajax()
                    ? response()->json($exception->errors(), 422)
                    : redirect()->back()->withInput($request->input())->withErrors($exception->errors());
            }
            return $this->errorResponse(422, $exception->getMessage(), $exception->errors());
        }

        if ($exception instanceof ModelNotFoundException) {
            $modelo = strtolower(class_basename($exception->getModel()));
            return $this->errorResponse(404, "No existe ninguna instancia de {$modelo} con el id especificado.");
        }
        
        if ($exception instanceof AuthenticationException) {
            if ($this->isFrontEnd($request)){
                return redirect()->guest('login');
            }
            return $this->errorResponse(401, "No autenticado.");
        }
        
        if ($exception instanceof AuthorizationException) {
            return $this->errorResponse(403, "No posee permisos para ejecutar esta acción.");
        }
        
        if ($exception instanceof NotFoundHttpException) {
            
            return $this->errorResponse(404, "No se encontró la URL especificado.");
        }
        
        if ($exception instanceof HttpException) {
            return $this->errorResponse($exception->getStatusCode(), $exception->getMessage());
        }

        if ($exception instanceof QueryException) {
            $codigo = $exception->errorInfo[1];
            if ($codigo == 1451) {
                return $this->errorResponse(409, "No se puede eliminar de forma permanente el recurso porque está relacionado con algún otro.");
            }
        }

        if ($exception instanceof TokenMismatchException) {
            return $this->redirect()->back()->withInput($request->input());
        }

        if (config("app.debug")) {
            return parent::render($request, $exception);
        }
        
        return $this->errorResponse(500, "Error interno.");
    }

    /**
     * Check if request is comming from frontend.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bolean $isFrontEnd
     */
    private function isFrontEnd($request)
    {
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains("web");
    }
}
