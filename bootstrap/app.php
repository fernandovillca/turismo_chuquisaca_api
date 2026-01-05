<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        // #### Manejo de modelo no encontrado
        // ###################################
        $exceptions->render(function (ModelNotFoundException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Recurso no encontrado',
                    'error' => 'El registro solicitado no existe'
                ], 404);
            }
        });

        // #### Manejo de ruta no encontrada
        // #################################
        $exceptions->render(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Endpoint no encontrado',
                    'error' => 'La ruta solicitada no existe'
                ], 404);
            }
        });

        // #### Manejo de método no permitido
        // ##################################
        $exceptions->render(function (MethodNotAllowedHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Método HTTP no permitido',
                    'error' => 'El método ' . $request->method() . ' no está permitido para esta ruta'
                ], 405);
            }
        });

        // #### Manejo de errores de autenticación
        // #######################################
        $exceptions->render(function (AuthenticationException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autenticado',
                    'error' => 'Debes iniciar sesión para acceder a este recurso'
                ], 401);
            }
        });

        // #### Manejo de errores de base de datos (como violación de restricciones)
        // #########################################################################
        $exceptions->render(function (QueryException $e, $request) {
            if ($request->is('api/*')) {
                // Errores de integridad referencial
                if ($e->getCode() === '23000') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Error de integridad de datos',
                        'error' => 'No se puede eliminar porque tiene registros relacionados'
                    ], 409);
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Error en la base de datos',
                    'error' => 'Ocurrió un error al procesar la solicitud'
                ], 500);
            }
        });

        // #### Manejo de otros errores HTTP
        // #################################
        $exceptions->render(function (HttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error HTTP',
                    'error' => $e->getMessage() ?: 'Ocurrió un error en el servidor'
                ], $e->getStatusCode());
            }
        });

        // #### Manejo de cualquier otra excepción no capturada
        // ####################################################
        $exceptions->render(function (Throwable $e, $request) {
            if ($request->is('api/*')) {
                // En producción, no mostrar detalles del error
                $message = app()->environment('production')
                    ? 'Ocurrió un error inesperado'
                    : $e->getMessage();

                return response()->json([
                    'success' => false,
                    'message' => 'Error del servidor',
                    'error' => $message,
                    // Solo en desarrollo/local
                    'trace' => app()->environment('local') ? $e->getTrace() : null
                ], 500);
            }
        });
    })->create();
