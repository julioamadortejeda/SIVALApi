<?php

namespace App\Exceptions;

use Exception;
use App\Traits\ApiResponser;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use League\OAuth2\Server\Exception\OAuthServerException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Asm89\Stack\CorsService;

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
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        if ($exception instanceof OAuthServerException) {
            $payload = [];

            if ($exception->getErrorType() === 'invalid_credentials') {
                $payload = [
                    'error' => trans('auth.credencialesInvalidas'),
                    'code' => 400
                ];
            }

            if ($exception->getErrorType() === 'invalid_request') {
                $payload = [
                    'error' => trans('auth.peticionInvalida'),
                    'code' => 400
                ];
            }

            if ($exception->getErrorType() === 'invalid_client') {
                $payload = [
                    'error' => trans('auth.clienteInvalido'),
                    'code' => 400
                ];
            }

            if (!empty($payload))
                $exception->setPayload($payload);
        }

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
        $response = $this->handleException($request, $exception);
        app(CorsService::class)->addActualRequestHeaders($response, $request);
        
        return $response;
    }

    public function handleException($request, Exception $exception)
    {
        if ($exception instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($exception, $request);
        }

        if ($exception instanceof ModelNotFoundException) {
            $modelo =  strtolower(class_basename($exception->getModel()));
            return $this->errorResponse("No existe ninguna instancia de {$modelo} con el id especificado.", 404);
        }

        if ($exception instanceof AuthenticationException) {
            return $this->unauthenticated($request, $exception);
        }

        if ($exception instanceof AuthorizationException) {
            return $this->errorResponse('No tiene permisos para ejecutar esta accion.', 403);
        }

        if ($exception instanceof NotFoundHttpException) {
            return $this->errorResponse('No se encontro la URL especificada.', 404);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->errorResponse('El metodo especificado el la peticion no es válido.', 405);
        }

        if ($exception instanceof HttpException) {
            return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
        }

        if ($exception instanceof QueryException) {
            $codigo = $exception->errorInfo[1];

            if ($codigo == 1451) {
                return $this->errorResponse('No se puede eliminar el recurso ya que esta relacionado con algun otro.', 409);
            }

            if ($codigo == 1062) {
                dd($exception);
                return $this->errorResponse('Ya existe un registro con los mismos datos.', 409);
            }
        }

        if (config('app.debug')) {
            //dd($exception);
            return $this->errorResponse($exception, 500);
            //return parent::render($request, $exception);
        }

        return $this->errorResponse('Falla insesperada. Intente mas tarde.', 500);
    }

    /**
     * Convert an authentication exception inyo an unauthentication response.
     * @param  \Illuminate\Http\Request  $request
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $this->errorResponse('No autenticado.', 401);
    }

    /**
     * Create a response object from the given validation exception.
     *
     * @param  \Illuminate\Validation\ValidationException  $e
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors = $e->validator->errors()->getMessages();

        return $this->errorResponse($errors, 422);
    }
}
