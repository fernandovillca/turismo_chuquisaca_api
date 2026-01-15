<?php

namespace App\Repositories\Api\V1;

use App\Models\Municipality;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class MunicipalityRepository
{
    /**
     * Obtiene todos los municipios paginados.
     * Cada municipio incluye la información de su región y sus comunidades.
     *
     * @param int $perPage Cantidad de registros por página.
     * @param string|null $languageCode Código del idioma (opcional)
     * @return LengthAwarePaginator Municipios paginados.
     */
    public function getAllPaginated(int $perPage = 10, ?string $languageCode = null): LengthAwarePaginator
    {
        $query = Municipality::with(['region', 'communities']);

        if ($languageCode) {
            $query->with(['translation' => function ($q) use ($languageCode) {
                $q->whereHas('language', function ($query) use ($languageCode) {
                    $query->where('code', $languageCode);
                })->with('language');
            }]);
        } else {
            $query->with(['translations.language']);
        }

        return $query->paginate($perPage);

        // return Municipality::with(['region', 'communities'])
        //     ->paginate($perPage);
    }

    /**
     * Obtiene todos los municipios activos paginados.
     * Cada municipio incluye la información de su región y sus comunidades.
     *
     * @param int $perPage Cantidad de registros por página.
     * @return LengthAwarePaginator Municipios activos paginados.
     */
    public function getAllActivePaginated(int $perPage = 10): LengthAwarePaginator
    {
        return Municipality::with(['region', 'communities'])
            ->where('is_active', true)
            ->paginate($perPage);
    }

    /**
     * Obtiene los municipios activos por región.
     * Cada municipio incluye la información de su región y sus comunidades.
     *
     * @param int $regionId Identificador de la región.
     * @return Collection Colección de municipios activos de la región.
     */
    public function getActiveByRegion(int $regionId): Collection
    {
        return Municipality::with(['region', 'communities'])
            ->where('region_id', $regionId)
            ->where('is_active', true)
            ->get();
    }

    /**
     * Busca un municipio por su identificador.
     * Incluye la información de su región y sus comunidades.
     *
     * @param int $id Identificador único del municipio.
     * @return Municipality|null Instancia del municipio o null si no existe.
     */
    public function findById(int $id): ?Municipality
    {
        return Municipality::with(['region', 'communities'])
            ->find($id);
    }

    /**
     * Crea un nuevo municipio.
     *
     * @param array $data Datos validados para la creación del municipio.
     * @return Municipality Municipio creado.
     */
    public function create(array $data): Municipality
    {
        return Municipality::create([
            'region_id' => $data['region_id'],
            'name' => $data['name'],
            'short_description' => $data['short_description'],
            'long_description' => $data['long_description'] ?? null,
            'latitud' => $data['latitud'],
            'longitud' => $data['longitud'],
            'address' => $data['address'],
            'image' => $data['image'],
            'is_active' => true,
        ]);
    }

    /**
     * Actualiza la información de un municipio existente.
     *
     * @param Municipality $municipality Instancia del municipio a actualizar.
     * @param array $data Datos validados para la actualización.
     * @return Municipality Municipio actualizado.
     */
    public function update(Municipality $municipality, array $data): Municipality
    {
        $municipality->update([
            'region_id' => $data['region_id'] ?? $municipality->region_id,
            'name' => $data['name'] ?? $municipality->name,
            'short_description' => $data['short_description'] ?? $municipality->short_description,
            'long_description' => $data['long_description'] ?? $municipality->long_description,
            'latitud' => $data['latitud'] ?? $municipality->latitud,
            'longitud' => $data['longitud'] ?? $municipality->longitud,
            'address' => $data['address'] ?? $municipality->address,
            'image' => $data['image'] ?? $municipality->image,
        ]);

        return $municipality->fresh(['region', 'communities']);
    }

    /**
     * Elimina un municipio.
     *
     * @param Municipality $municipality Instancia del municipio a eliminar.
     * @return bool Verdadero si la eliminación fue exitosa.
     */
    public function delete(Municipality $municipality): bool
    {
        return $municipality->delete();
    }

    /**
     * Cambia el estado de activación de un municipio.
     *
     * @param Municipality $municipality Instancia del municipio.
     * @return Municipality Municipio con el estado actualizado.
     */
    public function toggleActive(Municipality $municipality): Municipality
    {
        $municipality->update(['is_active' => !$municipality->is_active]);
        return $municipality->fresh();
    }

    /**
     * Actualiza el estado de todas los municipios de una región.
     * Este método se usa cuando se activa/inactiva una región.
     *
     * @param int $regionId Identificador de la región
     * @param bool $isActive Nuevo estado.
     * @return int Número de municipios actualizados.
     */
    public function updateStatusByRegion(int $regionId, bool $isActive): int
    {
        return Municipality::where('region_id', $regionId)
            ->update(['is_active' => $isActive]);
    }

    /**
     * Obtiene los IDs de los municipios asociados a una región.
     *
     * @param int $regionId Identificador de la región.
     * @return array Arreglo de IDs de municipios.
     */
    public function getIdsByRegion(int $regionId): array
    {
        return Municipality::where('region_id', $regionId)
            ->pluck('id')
            ->toArray();
    }
}
