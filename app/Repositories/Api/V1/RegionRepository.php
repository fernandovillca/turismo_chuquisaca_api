<?php

namespace App\Repositories\Api\V1;

use App\Models\Region;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class RegionRepository
{
    /**
     * Obtiene una lista paginada de regiones paginada.
     *
     * @param int $perPage Cantidad de registros por página.
     * @return LengthAwarePaginator Colección paginada de regiones.
     */
    public function getAllPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return Region::paginate($perPage);
    }

    /**
     * Obtiene todas las regiones sin paginación.
     *
     * @return Collection Colección completa de regiones.
     */
    public function getAll(): Collection
    {
        return Region::all();
    }

    /**
     * Obtiene todas las regiones activas.
     *
     * Retorna únicamente las regiones cuyo estado se encuentra activo.
     *
     * @return Collection Colección de regiones activas.
     */
    public function getActive(): Collection
    {
        return Region::where('is_active', true)->get();
    }

    /**
     * Busca una región por su identificador.
     *
     * @param int $id Identificador único de la región.
     * @return Region|null Instancia de la región o null si no existe.
     */
    public function findById(int $id): ?Region
    {
        return Region::find($id);
    }


    /**
     * Crea una nueva región.
     *
     * @param array $data Datos validados para la creación de la región.
     * @return Region Región creada.
     */
    public function create(array $data): Region
    {
        return Region::create($data);
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
        $region->update($data);
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
     * Si la región está activa, será desactivada; si está inactiva, será activada.
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
