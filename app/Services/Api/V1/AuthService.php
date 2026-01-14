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
}
