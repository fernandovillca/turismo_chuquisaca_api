<?php

namespace App\Services\Api\V1;

use App\Models\Community;
use App\Repositories\Api\V1\CommunityRepository;
use App\Repositories\Api\V1\MunicipalityRepository;
use Illuminate\Database\Eloquent\Collection;
use Exception;

class CommunityService
{
    protected CommunityRepository $communityRepository;
    protected MunicipalityRepository $municipalityRepository;

    public function __construct(
        CommunityRepository $communityRepository,
        MunicipalityRepository $municipalityRepository
    ) {
        $this->communityRepository = $communityRepository;
        $this->municipalityRepository = $municipalityRepository;
    }

    /**
     * Obtener todas las comunidades
     *
     * @return Collection Colección de comunidades
     */
    public function getAllCommunities(): Collection
    {
        return $this->communityRepository->getAll();
    }

    /**
     * Obtener todas las comunidades activas
     *
     * @return Collection Colección de comunidades activas
     */
    public function getActiveCommunities(): Collection
    {
        return $this->communityRepository->getAllActive();
    }

    /**
     * Obtener comunidades activas por municipio
     *
     * @param int $municipalityId ID del municipio
     * @return Collection
     */
    public function getActiveCommunitiesByMunicipality(int $municipalityId): Collection
    {
        $municipality = $this->municipalityRepository->findById($municipalityId);

        if (!$municipality) {
            throw new Exception("El municipio con ID {$municipalityId} no existe", 404);
        }

        return $this->communityRepository->getActiveByMunicipality($municipalityId);
    }

    /**
     * Obtener una comunidad por su ID
     *
     * @param int $id ID de la comunidad
     * @return Community
     */
    public function getCommunityById(int $id): Community
    {
        $community = $this->communityRepository->findById($id);

        if (!$community) {
            throw new Exception("La comunidad con ID {$id} no existe", 404);
        }

        return $community;
    }

    /**
     * Crear una nueva comunidad
     *
     * @param array $data Datos de la comunidad
     * @return Community
     */
    public function createCommunity(array $data): Community
    {
        $municipality = $this->municipalityRepository->findById($data['municipality_id']);

        if (!$municipality) {
            throw new Exception("El municipio con ID {$data['municipality_id']} no existe", 404);
        }

        if (!$municipality->is_active) {
            throw new Exception("No se puede crear una comunidad en un municipio inactivo", 409);
        }

        return $this->communityRepository->create($data);
    }

    /**
     * Actualizar una comunidad existente
     *
     * @param int $id ID de la comunidad
     * @param array $data Datos a actualizar
     * @return Community
     */
    public function updateCommunity(int $id, array $data): Community
    {
        $community = $this->communityRepository->findById($id);

        if (!$community) {
            throw new Exception("La comunidad con ID {$id} no existe", 404);
        }

        if (isset($data['municipality_id'])) {
            $municipality = $this->municipalityRepository->findById($data['municipality_id']);

            if (!$municipality) {
                throw new Exception("El municipio con ID {$data['municipality_id']} no existe", 404);
            }

            if (!$municipality->is_active) {
                throw new Exception("No se puede asignar una comunidad a un municipio inactivo", 409);
            }
        } else {
            throw new Exception("El ID del municipio es obligatorio para actualizar la comunidad", 400);
        }

        return $this->communityRepository->update($community, $data);
    }

    /**
     * Eliminar una comunidad
     *
     * @param int $id ID de la comunidad
     * @return bool
     */
    public function deleteCommunity(int $id): bool
    {
        $community = $this->communityRepository->findById($id);

        if (!$community) {
            throw new Exception("La comunidad con ID {$id} no existe", 404);
        }

        return $this->communityRepository->delete($community);
    }

    /**
     * Cambiar el estado de activación de una comunidad
     *
     * @param int $id ID de la comunidad
     * @return Community
     */
    public function toggleCommunityStatus(int $id): Community
    {
        $community = $this->communityRepository->findById($id);

        if (!$community) {
            throw new Exception("La comunidad con ID {$id} no existe", 404);
        }

        return $this->communityRepository
            ->toggleActive($community)
            ->fresh();
    }
}
