<?php

namespace App\Services\Api\V1;

use App\Repositories\Api\V1\RegionRepository;
use App\Models\Region;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class RegionService
{
    protected RegionRepository $regionRepository;

    public function __construct(RegionRepository $regionRepository)
    {
        $this->regionRepository = $regionRepository;
    }

    /**
     * Obtiene todas las regiones.
     *
     * @return Collection Colección paginada de regiones.
     */
    public function getAllRegions(): Collection
    {
        return $this->regionRepository->getAll();
    }

    /**
     * Obtiene todas las regiones activas.
     *
     * @return Collection Colección de regiones activas.
     */
    public function getActiveRegions(): Collection
    {
        return $this->regionRepository->getAllActive();
    }

    /**
     * Obtiene una región por su identificador.
     *
     * @param int $id Identificador único de la región.
     * @return Region|null Instancia de la región o null si no existe.
     */
    public function getRegionById(int $id): ?Region
    {
        return $this->regionRepository->findById($id);
    }

    /**
     * Crea una nueva región.
     *
     * @param array $data Datos validados para la creación de la región.
     * @return Region Región recién creada.
     */
    public function createRegion(array $data): Region
    {
        return $this->regionRepository->create($data);
    }

    /**
     * Actualiza una región existente.
     *
     * @param Region $region Instancia de la región a actualizar.
     * @param array $data Datos validados para la actualización.
     * @return Region Región actualizada.
     */
    public function updateRegion(Region $region, array $data): Region
    {
        return $this->regionRepository->update($region, $data);
    }

    /**
     * Elimina una región.
     *
     * @param Region $region Instancia de la región a eliminar.
     * @return bool Verdadero si la eliminación fue exitosa.
     */
    public function deleteRegion(Region $region): bool
    {
        // TODO:  agregar validaciones de negocio, verificar si tiene registros relacionados

        return $this->regionRepository->delete($region);
    }

    /**
     * Cambia el estado de activación de una región.
     *
     * Si la región está activa, será desactivada; si está inactiva, será activada.
     *
     * @param Region $region Instancia de la región.
     * @return Region Región con el estado actualizado.
     */
    public function toggleRegionStatus(Region $region): Region
    {
        return $this->regionRepository->toggleActive($region);
    }
}
