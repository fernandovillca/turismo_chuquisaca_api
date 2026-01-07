<?php

namespace App\Repositories\Api\V1;

use App\Models\Community;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CommunityRepository
{
    /**
     * Obtiene todas las comunidades paginadas.
     * Cada comunidad incluye la información de su municipio.
     *
     * @return Collection Comunidades paginadas.
     */
    public function getAll(): Collection
    {
        return Community::with('municipality')
            ->get();
    }

    /**
     * Obtiene todas las comunidades activas paginadas.
     * Cada comunidad incluye la información de su municipio.
     *
     * @return Collection Comunidades activas paginadas.
     */
    public function getAllActive(): Collection
    {
        return Community::with('municipality')
            ->where('is_active', true)
            ->get();
    }

    /**
     * Obtiene las comunidades activas por municipio.
     * Cada comunidad incluye la información de su municipio.
     *
     * @param int $municipalityId Identificador del municipio.
     * @return Collection Colección de comunidades activas del municipio.
     */
    public function getActiveByMunicipality(int $municipalityId): Collection
    {
        return Community::with('municipality')
            ->where('municipality_id', $municipalityId)
            ->where('is_active', true)
            ->get();
    }

    /**
     * Busca una comunidad por su identificador.
     * Incluye la información de su municipio.
     *
     * @param int $id Identificador único de la comunidad.
     * @return Community|null Instancia de la comunidad o null si no existe.
     */
    public function findById(int $id): ?Community
    {
        return Community::with('municipality')
            ->find($id);
    }

    /**
     * Crea una nueva comunidad.
     *
     * @param array $data Datos validados para la creación de la comunidad.
     * @return Community Comunidad creada.
     */
    public function create(array $data): Community
    {
        return Community::create([
            'municipality_id' => $data['municipality_id'],
            'name' => $data['name'],
            'short_description' => $data['short_description'] ?? null,
            'address' => $data['address'] ?? null,
            'is_active' => true,
        ]);
    }

    /**
     * Actualiza la información de una comunidad existente.
     *
     * @param Community $community Instancia de la comunidad a actualizar.
     * @param array $data Datos validados para la actualización.
     * @return Community Comunidad actualizada.
     */
    public function update(Community $community, array $data): Community
    {
        $community->update([
            'municipality_id' => $data['municipality_id'] ?? $community->municipality_id,
            'name' => $data['name'] ?? $community->name,
            'short_description' => $data['short_description'] ?? $community->short_description,
            'address' => $data['address'] ?? $community->address,
        ]);

        return $community->fresh('municipality');
    }

    /**
     * Elimina una comunidad.
     *
     * @param Community $community Instancia de la comunidad a eliminar.
     * @return bool Verdadero si la eliminación fue exitosa.
     */
    public function delete(Community $community): bool
    {
        return $community->delete();
    }

    /**
     * Cambia el estado de activación de una comunidad.
     *
     * @param Community $community Instancia de la comunidad.
     * @return Community Comunidad con el estado actualizado.
     */
    public function toggleActive(Community $community): Community
    {
        $community->update(['is_active' => !$community->is_active]);
        return $community->fresh('municipality');
    }

    /**
     * Actualiza el estado de todas las comunidades de un municipio.
     * Este método se usa cuando se activa/inactiva un municipio.
     *
     * @param int $municipalityId Identificador del municipio.
     * @param bool $isActive Nuevo estado.
     * @return int Número de comunidades actualizadas.
     */
    public function updateStatusByMunicipality(array $municipalityIds, bool $isActive): int
    {
        return Community::whereIn('municipality_id', $municipalityIds)
            ->update(['is_active' => $isActive]);
    }
}
