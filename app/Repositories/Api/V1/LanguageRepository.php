<?php

namespace App\Repositories\Api\V1;

use App\Models\Language;
use Illuminate\Database\Eloquent\Collection;

class LanguageRepository
{
    /**
     * Obtiene todos los idiomas sin paginación.
     *
     * @return Collection Colección completa de idiomas.
     */
    public function getAll(): Collection
    {
        return Language::all();
    }

    /**
     * Busca un idioma por su identificador.
     *
     * @param int $id Identificador único del idioma.
     * @return Language|null Instancia del idioma o null si no existe.
     */
    public function findById(int $id): ?Language
    {
        return Language::find($id);
    }

    /**
     * Crea un nuevo idioma.
     *
     * @param array $data Datos validados para la creación del idioma.
     * @return Language Idioma creado.
     */
    public function create(array $data): Language
    {
        return Language::create([
            'code' => $data['code'],
            'name' => $data['name'],
            'is_active' => true,
        ]);
    }

    /**
     * Actualiza la información de un idioma existente.
     *
     * @param Language $language Instancia del idioma a actualizar.
     * @param array $data Datos validados para la actualización.
     * @return Language Idioma actualizado.
     */
    public function update(Language $language, array $data): Language
    {
        $language->update([
            'code' => $data['code'] ?? $language->code,
            'name' => $data['name'] ?? $language->name,
            'is_active' => $data['is_active'] ?? $language->is_active,
        ]);

        return $language->fresh();
    }

    /**
     * Elimina un idioma.
     *
     * @param Language $language Instancia del idioma a eliminar.
     * @return bool Verdadero si la eliminación fue exitosa.
     */
    public function delete(Language $language): bool
    {
        return $language->delete();
    }

    /**
     * Cambia el estado de activación de un idioma.
     *
     * @param Language $language Instancia del idioma.
     * @return Language Idioma con el estado actualizado.
     */
    public function toggleActive(Language $language): Language
    {
        $language->update(['is_active' => !$language->is_active]);
        return $language->fresh();
    }
}
