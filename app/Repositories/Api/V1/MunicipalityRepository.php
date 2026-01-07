<?php

namespace App\Repositories\Api\V1;

use App\Models\Municipality;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class MunicipalityRepository
{
    /**
     * TODO:
     * Obtener todos los municipios paginados
     *
     * Con su la informacion de su región y sus comunidades
     */

    /**
     * TODO:
     * Obtener todos los municipios activos paginados
     *
     * Con su la informacion de su región y sus comunidades
     */

    /**
     * TODO:
     * Obtener los municipios activos por region (region_id)
     *
     * Con su la informacion de su región y sus comunidades
     */

    /**
     * TODO:
     * Buscar un municipio por su ID
     *
     * Con su la informacion de su región y sus comunidades
     */

    /**
     * TODO:
     * Crear un nuevo municipio
     *
     */

    /**
     * TODO:
     * Actualizar un municipio existente
     *
     */

    /**
     * TODO:
     * Eliminar un municipio
     *
     * solo si no tiene comunidades asociadas
     *
     */

    /**
     * TODO:
     * Cambiar el estado activo/inactivo de un municipio
     *
     * Si se activa/inactiva un municipio, se deben activar/inactivar
     * todas sus comunidades asociadas
     *
     */

    /**
     * TODO:
     * Actualizar la imagen de un municipio
     *
     */


    /**
     * Obtener todos los municipios paginados
     *
     * Con la relación de región cargada y solo los municipios activos
     *
     * @param int $perPage Cantidad de registros por página por
     * @return LengthAwarePaginator
     */
    public function getAllPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return Municipality::with('region')
            ->where('is_active', true)
            ->paginate($perPage);
    }

    /**
     * Obtener todos los municipios sin paginar
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return Municipality::with('region')->get();
    }

    /**
     * Obtener solo los municipios activos
     *
     * @return Collection
     */
    public function getActive(): Collection
    {
        return Municipality::with('region')
            ->where('is_active', true)
            ->get();
    }

    /**
     * Obtener municipios activos de una región específica
     *
     * @param int $regionId ID de la región
     * @return Collection
     */
    public function getActiveByRegion(int $regionId): Collection
    {
        return Municipality::where('region_id', $regionId)
            ->where('is_active', true)
            ->get();
    }

    /**
     * Obtener todos los municipios de una región específica
     *
     * @param int $regionId ID de la región
     * @param int $perPage Cantidad de registros por página
     * @return LengthAwarePaginator
     */
    public function getByRegion(int $regionId, int $perPage = 10): LengthAwarePaginator
    {
        return Municipality::with('region')
            ->where('region_id', $regionId)
            ->paginate($perPage);
    }

    /**
     * Buscar un municipio por su ID
     *
     * @param int $id ID del municipio
     * @return Municipality|null
     */
    public function findById(int $id): ?Municipality
    {
        return Municipality::with('region')->find($id);
    }

    /**
     * Crear un nuevo municipio
     *
     * @param array $data Datos del municipio a crear
     * @return Municipality
     */
    public function create(array $data): Municipality
    {
        return Municipality::create($data);
    }

    /**
     * Actualizar un municipio existente
     *
     * @param Municipality $municipality Instancia del municipio a actualizar
     * @param array $data Datos a actualizar
     * @return Municipality Municipio actualizado
     */
    public function update(Municipality $municipality, array $data): Municipality
    {
        $municipality->update($data);
        return $municipality->fresh('region');
    }

    /**
     * Eliminar un municipio
     *
     * @param Municipality $municipality Instancia del municipio a eliminar
     * @return bool True si se eliminó correctamente
     */
    public function delete(Municipality $municipality): bool
    {
        return $municipality->delete();
    }

    /**
     * Actualizar la imagen de un municipio
     *
     * @param Municipality $municipality Instancia del municipio
     * @param string $imagePath Ruta de la nueva imagen
     * @return Municipality Municipio actualizado
     */
    public function updateImage(Municipality $municipality, string $imagePath): Municipality
    {
        $municipality->update(['image' => $imagePath]);
        return $municipality->fresh('region');
    }
}
