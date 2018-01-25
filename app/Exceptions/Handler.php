<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
        ModelNotFoundException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */

        /**
         * @api {get} / Renderiza una excepción dentro de una respuesta Http, Ejemplo:
         * @apiVersion 0.1.0
         * @apiName Sistema
         * @apiGroup Handler
         * 
         * @apiError BadRequest Petición errónea
         * @apiError Unauthorized No autorizado
         * @apiError Forbidden Prohibido
         * @apiError NotFound No se encuentra
         * @apiError MethodNotAllowed Método no permitido
         * @apiError NotAceptable No aceptable
         * @apiError Conflict Conflicto
         * @apiError Gone Recurso ya no existe
         * @apiError URITooLong Dirección demasiado larga
         * @apiError InternalServerError Error interno del servidor
         * @apiError NotImplement No implementado
         * @apiError ServiceUnavailable Servicio no disponible
         * 
         */

    public function render($request, Exception $e)
    {
        if ($e instanceof ModelNotFoundException) {
            $e = new NotFoundHttpException($e->getMessage(), $e);
            return response()->view('errors.allPagesError', ['icon' => 'question', 'error' => '404', 'title' => 'Not found / No se encuentra', 'message' => 'El servidor no puede encontrar el recurso solicitado y no es posible determinar si esta ausencia es temporal o permanente.'], 404);
        }
        if ($e instanceof \Bican\Roles\Exceptions\RoleDeniedException) {
            return response()->view('errors.allPagesError', ['icon' => 'ban', 'error' => '401', 'title' => 'Unauthorized / No autorizado', 'message' => 'Error con los permisos.'], 401);
        }

        if ($this->isHttpException($e)) {
            if ($e->getStatusCode() == 400) {
                    return response()->view('errors.allPagesError', ['icon' => 'question', 'error' => '400', 'title' => 'Bad request / Petición erronea', 'message' => 'El servidor no es capaz de entender la petición porque su sintaxis no es correcta.'], 400);
            }
            if ($e->getStatusCode() == 401) {
                    return response()->view('errors.allPagesError', ['icon' => 'ban', 'error' => '401', 'title' => 'Unauthorized / No autorizado', 'message' => 'El recurso solicitado requiere de autenticación.'], 401);
            }
            if ($e->getStatusCode() == 403) {
                    return response()->view('errors.allPagesError', ['icon' => 'user-secret', 'error' => '403', 'title' => 'Forbidden / Prohibido', 'message' => 'No es posible acceder al recurso solicitado. Se ha denegado el acceso.'], 403);
            }
            if ($e->getStatusCode() == 404) {
                    return response()->view('errors.allPagesError', ['icon' => 'search-minus', 'error' => '404', 'title' => 'Not found / No se encuentra', 'message' => 'El servidor no puede encontrar el recurso solicitado y no es posible determinar si esta ausencia es temporal o permanente.'], 404);
            }
            if ($e->getStatusCode() == 405) {
                    return response()->view('errors.allPagesError', ['icon' => 'shield', 'error' => '405', 'title' => 'Method not allowed / Método no permitido', 'message' => 'El navegador ha utilizado un método no permitido por el servidor para obtener ese recurso.'], 405);
            }
            if ($e->getStatusCode() == 406) {
                    return response()->view('errors.allPagesError', ['icon' => 'remove', 'error' => '406', 'title' => ' Not acceptable / No aceptable', 'message' => 'El recurso solicitado tiene un formato que en teoría no es aceptable por el navegador.'], 406);
            }
            if ($e->getStatusCode() == 409) {
                    return response()->view('errors.allPagesError', ['icon' => 'flash', 'error' => '409', 'title' => 'Conflict / Conflicto', 'message' => 'La petición no se ha podido completar porque se ha producido un conflicto con el recurso solicitado.'], 409);
            }
            if ($e->getStatusCode() == 410) {
                    return response()->view('errors.allPagesError', ['icon' => 'question', 'error' => '410', 'title' => 'Gone / Recurso ya no existe', 'message' => 'No es posible encontrar el recurso solicitado y esta ausencia se considera permanente. '], 410);
            }
            if ($e->getStatusCode() == 414) {
                    return response()->view('errors.allPagesError', ['icon' => 'unlink', 'error' => '414', 'title' => 'Request URI too long / Dirección demasiado larga', 'message' => 'La URI de la petición es demasiado extensa y por ese motivo el servidor no la procesa.'], 414);
            }
            if ($e->getStatusCode() == 500) {
                    return response()->view('errors.allPagesError', ['icon' => 'warning', 'error' => '500', 'title' => 'Internal Server Error / Error interno del servidor', 'message' => 'La solicitud no se ha podido completar porque se ha producido un error inesperado en el servidor.'], 500);
            }
            if ($e->getStatusCode() == 501) {
                    return response()->view('errors.allPagesError', ['icon' => 'wheelchair', 'error' => '501', 'title' => 'Not implemented / No implementado', 'message' => 'El servidor no soporta alguna funcionalidad necesaria para responder a la solicitud.'], 501);
            }
            if ($e->getStatusCode() == 503) {
                    return response()->view('errors.allPagesError', ['icon' => 'wrench', 'error' => '503', 'title' => 'Service Unavailable / Servicio no disponible', 'message' => 'El servidor no puede responder a la petición porque está congestionado o está realizando tareas de mantenimiento.'], 503);
            }
            return parent::render($request, $e);
        }
        else {
            if (app()->environment() == 'production') {
                return response()->view('errors.allPagesError', ['icon' => 'warning', 'error' => '500', 'title' => 'Internal Server Error / Error interno del servidor', 'message' => 'La solicitud no se ha podido completar porque se ha producido un error inesperado en el servidor.'], 500);
            }
            return parent::render($request, $e);
        }
    }
}
