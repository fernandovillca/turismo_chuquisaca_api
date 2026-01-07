<?php

namespace App\Repositories\Api\V1;

use App\Models\Region;
use Illuminate\Database\Eloquent\Collection;

class RegionRepository
{
    /**
     * Obtiene todas las regiones sin paginación.
     * Cada region incluye la informacion de sus municipios.
     *
     * @return Collection Colección completa de regiones.
     */
    public function getAll(): Collection
    {
        return Region::with('municipalities.communities')
            ->get();
    }

    /**
     * Obtiene todas las regiones activas sin paginación.
     * Cada region incluye la informacion de sus municipios.
     *
     * @return Collection Colección completa de regiones activas.
     */

    public function getAllActive(): Collection
    {
        return Region::with('municipalities.communities')
            ->where('is_active', true)
            ->get();
    }

    /**
     * Busca una región por su identificador junto con sus municipios.
     *
     * @param int $id Identificador único de la región.
     * @return Region|null Instancia de la región o null si no existe.
     */
    public function findById(int $id): ?Region
    {
        return Region::with('municipalities.communities')
            ->find($id);
    }

    /**
     * Crea una nueva región.
     *
     * @param array $data Datos validados para la creación de la región.
     * @return Region Región creada.
     */
    public function create(array $data): Region
    {
        return Region::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'is_active' => true,
        ]);
    }

    /**
     * Actualiza la información de una región existente.
     *
     * @param Region $region Instancia de la región a actualizar.
     * @param array $data Datos validados para la actualización.
     * @return Region Región actualizada.
     */
    public function update(Region $region, array $data): Region
    {
        $region->update([
            'name' => $data['name'] ?? $region->name,
            'description' => $data['description'] ?? $region->description,
        ]);

        return $region->fresh();
    }

    /**
     * Elimina una región.
     *
     * @param Region $region Instancia de la región a eliminar.
     * @return bool Verdadero si la eliminación fue exitosa.
     */
    public function delete(Region $region): bool
    {
        return $region->delete();
    }

    /**
     * Cambia el estado de activación de una región.
     *
     * @param Region $region Instancia de la región.
     * @return Region Región con el estado actualizado.
     */
    public function toggleActive(Region $region): Region
    {
        $region->update(['is_active' => !$region->is_active]);
        return $region->fresh();
    }
}
