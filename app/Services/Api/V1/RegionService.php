<?php

namespace App\Services\Api\V1;

use App\Repositories\Api\V1\RegionRepository;
use App\Models\Region;
use App\Repositories\Api\V1\CommunityRepository;
use App\Repositories\Api\V1\MunicipalityRepository;
use Illuminate\Database\Eloquent\Collection;
use Exception;

class RegionService
{
    protected RegionRepository $regionRepository;
    protected MunicipalityRepository $municipalityRepository;
    protected CommunityRepository $communityRepository;

    public function __construct(
        RegionRepository $regionRepository,
        MunicipalityRepository $municipalityRepository,
        CommunityRepository $communityRepository
    ) {
        $this->regionRepository = $regionRepository;
        $this->municipalityRepository = $municipalityRepository;
        $this->communityRepository = $communityRepository;
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
        $region = $this->regionRepository->findById($id);

        if (!$region) {
            throw new Exception("La región con ID {$id} no existe", 404);
        }
        return $region;
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
     * @param int $id Identificador de la región a actualizar.
     * @param array $data Datos validados para la actualización.
     * @return Region Región actualizada.
     */
    public function updateRegion(int $id, array $data): Region
    {
        $region = $this->regionRepository->findById($id);

        if (!$region) {
            throw new Exception("La región con ID {$id} no existe", 404);
        }

        return $this->regionRepository->update($region, $data);
    }

    /**
     * Elimina una región.
     *
     * @param int $id Identificador de la región a eliminar.
     * @return bool Verdadero si la eliminación fue exitosa.
     */
    public function deleteRegion(int $id): bool
    {
        $region = $this->regionRepository->findById($id);

        if (!$region) {
            throw new Exception("La región con ID {$id} no existe", 404);
        }

        if ($region->municipalities()->count() > 0) {
            throw new Exception('No se puede eliminar la región porque tiene municipios asociados', 409);
        }

        return $this->regionRepository->delete($region);
    }

    /**
     * Cambia el estado de activación de una región.
     * Si el estado cambia, tambien se cambia el estado de sus municipios asociados.
     * (y las comunidades de esos municipios en cascada).
     *
     * @param int $id Identificador de la región.
     * @return Region Región con el estado actualizado.
     */
    public function toggleRegionStatus(int $id): Region
    {
        $region = $this->regionRepository->findById($id);

        if (!$region) {
            throw new Exception("La región con ID {$id} no existe", 404);
        }

        $updatedRegion = $this->regionRepository->toggleActive($region);

        $this->municipalityRepository->updateStatusByRegion(
            $region->id,
            $updatedRegion->is_active
        );

        $municipalityIds = $this->municipalityRepository
            ->getIdsByRegion($region->id);

        if (!empty($municipalityIds)) {
            $this->communityRepository->updateStatusByMunicipality(
                $municipalityIds,
                $updatedRegion->is_active
            );
        }

        return $updatedRegion->fresh(['municipalities.communities']);
    }
}
