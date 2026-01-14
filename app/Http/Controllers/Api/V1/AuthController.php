<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Http\Requests\Api\V1\LoginRequest;
use App\Http\Requests\Api\V1\RegisterRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Services\Api\V1\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Database\QueryException;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $result = $this->authService->register($request->validated());

            return response()->json([
                'status_code' => 201,
                'message' => 'Usuario registrado exitosamente',
                'data' => [
                    'user' => new UserResource($result['user']),
                    'token' => $result['token'],
                    'token_type' => 'Bearer'
                ]
            ], 201);
        } catch (QueryException $e) {
            return ApiResponse::error(
                'Error de base de datos',
                'No se pudo crear el usuario en la base de datos',
                500
            );
        } catch (Exception $e) {
            return ApiResponse::error('Error al registrar usuario', $e->getMessage(), 400);
        }
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $result = $this->authService->login($request->validated());

            return response()->json([
                'status_code' => 200,
                'message' => 'Inicio de sesión exitoso',
                'data' => [
                    'user' => new UserResource($result['user']),
                    'token' => $result['token'],
                    'token_type' => 'Bearer'
                ]
            ]);
        } catch (Exception $e) {
            return ApiResponse::error('Error al iniciar sesión', $e->getMessage(), 401);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $this->authService->logout($request->user()->id);

            return response()->json([
                'status_code' => 200,
                'message' => 'Sesión cerrada exitosamente'
            ]);
        } catch (Exception $e) {
            return ApiResponse::error('Error al cerrar sesión', $e->getMessage(), 500);
        }
    }
}
