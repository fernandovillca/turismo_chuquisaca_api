<?php

namespace App\Repositories\Api\V1;

use App\Models\User;

class UserRepository
{
    /**
     * Crear un nuevo usuario
     *
     * @param array $data Datos del usuario
     * @return User
     */
    public function create(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => $data['role'] ?? 'usuario',
        ]);
    }

    /**
     * Crear un token de acceso para el usuario
     *
     * @param User $user Instancia del usuario
     * @param string $tokenName Nombre del token
     * @return string Token en texto plano
     */
    public function createToken(User $user, string $tokenName = 'auth-token'): string
    {
        return $user->createToken($tokenName)->plainTextToken;
    }

    /**
     * Buscar un usuario por su email
     *
     * @param string $email Email del usuario
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    /**
     * Eliminar todos los tokens de un usuario
     *
     * @param User $user Instancia del usuario
     * @return void
     */
    public function deleteAllTokens(User $user): void
    {
        $user->tokens()->delete();
    }
}
