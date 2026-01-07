<?php

namespace App\Services\Api\V1;

use App\Repositories\Api\V1\RegionRepository;
use App\Models\Region;
use App\Repositories\Api\V1\MunicipalityRepository;
use Illuminate\Database\Eloquent\Collection;
use Exception;

class RegionService
{
    protected RegionRepository $regionRepository;
    protected MunicipalityRepository $municipalityRepository;

    public function __construct(
        RegionRepository $regionRepository,
        MunicipalityRepository $municipalityRepository
    ) {
        $this->regionRepository = $regionRepository;
        $this->municipalityRepository = $municipalityRepository;
    }

    /**
     * Obtiene todas las regiones.
     *
     * @return Collection Colección de regiones.
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
        if ($region->municipalities()->count() > 0) {
            throw new Exception('No se puede eliminar la región porque tiene municipios asociados');
        }

        return $this->regionRepository->delete($region);
    }

    /**
     * Cambia el estado de activación de una región.
     * Si el estado cambia, tambien se cambia el estado de sus municipios asociados.
     * (y las comunidades de esos municipios en cascada).
     *
     * @param Region $region Instancia de la región.
     * @return Region Región con el estado actualizado.
     */
    public function toggleRegionStatus(Region $region): Region
    {
        $updatedRegion = $this->regionRepository->toggleActive($region);

        $this->municipalityRepository->updateStatusByRegion(
            $region->id,
            $updatedRegion->is_active
        );

        return $updatedRegion->fresh(['municipalities.communities']);
    }
}
