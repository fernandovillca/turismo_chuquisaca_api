<?php

namespace App\Services\Api\V1;

use App\Repositories\Api\V1\UserRepository;
use Exception;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Registrar un nuevo usuario
     *
     * @param array $data Datos del usuario
     * @return array Usuario y token
     */
    public function register(array $data): array
    {
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'] ?? 'user',
        ];

        $user = $this->userRepository->create($userData);

        if (!$user) {
            throw new Exception('No se pudo crear el usuario');
        }

        $token = $this->userRepository->createToken($user, 'auth-token');

        if (!$token) {
            throw new Exception('No se pudo crear el token de autenticación');
        }

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    /**
     * Iniciar sesión
     *
     * @param array $credentials Credenciales de acceso
     * @return array Usuario y token
     */
    public function login(array $credentials): array
    {
        $user = $this->userRepository->findByEmail($credentials['email']);

        if (!$user) {
            throw new Exception('Credenciales incorrectas');
        }

        if (!Hash::check($credentials['password'], $user->password)) {
            throw new Exception('Credenciales incorrectas');
        }

        //(solo una sesión activa)
        $this->userRepository->deleteAllTokens($user);

        $token = $this->userRepository->createToken($user, 'auth-token');

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    /**
     * Cerrar sesión (eliminar token actual)
     *
     * @param int $id ID del usuario autenticado
     * @return bool
     */
    public function logout(int $id): bool
    {
        $user = $this->userRepository->findById($id);
        if (!$user) {
            throw new Exception('Usuario no encontrado');
        }

        $this->userRepository->deleteCurrentToken($user);

        return true;
    }
}
