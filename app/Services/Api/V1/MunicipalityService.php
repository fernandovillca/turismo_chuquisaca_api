<?php

namespace App\Services\Api\V1;

use App\Repositories\Api\V1\MunicipalityRepository;
use App\Repositories\Api\V1\RegionRepository;
use App\Models\Municipality;
use App\Repositories\Api\V1\CommunityRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Exception;

class MunicipalityService
{
    protected MunicipalityRepository $municipalityRepository;
    protected RegionRepository $regionRepository;
    protected CommunityRepository $communityRepository;

    public function __construct(
        MunicipalityRepository $municipalityRepository,
        RegionRepository $regionRepository,
        CommunityRepository $communityRepository
    ) {
        $this->municipalityRepository = $municipalityRepository;
        $this->regionRepository = $regionRepository;
        $this->communityRepository = $communityRepository;
    }

    /**
     * Obtener todos los municipios paginados
     *
     * @param int $perPage Cantidad de registros por página
     * @return LengthAwarePaginator
     */
    public function getAllMunicipalities(int $perPage = 10): LengthAwarePaginator
    {
        return $this->municipalityRepository->getAllPaginated($perPage);
    }

    /**
     * Obtener todos los municipios activos paginados
     *
     * @param int $perPage Cantidad de registros por página
     * @return LengthAwarePaginator
     */
    public function getActiveMunicipalities(int $perPage = 10): LengthAwarePaginator
    {
        return $this->municipalityRepository->getAllActivePaginated($perPage);
    }

    /**
     * Obtiene los municipios activos por región.
     *
     * @param int $regionId Identificador de la región.
     * @return Collection Colección de municipios activos de la región.
     */
    public function getActiveMunicipalitiesByRegion(int $regionId): Collection
    {
        $region = $this->regionRepository->findById($regionId);
        if (!$region) {
            throw new Exception("La región con ID {$regionId} no existe");
        }

        return $this->municipalityRepository->getActiveByRegion($regionId);
    }

    /**
     * Obtener un municipio por su ID
     *
     * @param int $id ID del municipio
     * @return Municipality|null
     */
    public function getMunicipalityById(int $id): ?Municipality
    {
        $municipality = $this->municipalityRepository->findById($id);

        if (!$municipality) {
            throw new Exception("El municipio con ID {$id} no existe", 404);
        }

        return $municipality;
    }

    /**
     * Crear un nuevo municipio
     *
     * @param array $data Datos del municipio
     * @return Municipality
     */
    public function createMunicipality(array $data, UploadedFile $image): Municipality
    {
        $region = $this->regionRepository->findById($data['region_id']);
        if (!$region) {
            throw new Exception("La región con ID {$data['region_id']} no existe");
        }

        if (!$region->is_active) {
            throw new Exception("No se puede crear un municipio en una región inactiva");
        }

        $imagePath = $this->saveImage($image);
        $data['image'] = $imagePath;

        return $this->municipalityRepository->create($data);
    }

    /**
     * Actualizar un municipio existente
     *
     * @param int $id Identificador del municipio a actualizar
     * @param array $data Datos a actualizar
     * @return Municipality
     */
    public function updateMunicipality(
        int $id,
        array $data,
        ?UploadedFile $image = null
    ): Municipality {
        $municipality = $this->municipalityRepository->findById($id);
        if (!$municipality) {
            throw new Exception("El municipio con ID {$id} no existe", 404);
        }

        if (isset($data['region_id'])) {

            $region = $this->regionRepository->findById($data['region_id']);

            if (!$region) {
                throw new Exception("La región con ID {$data['region_id']} no existe", 404);
            }

            if (!$region->is_active) {
                throw new Exception("No se puede crear un municipio en una región inactiva", 400);
            }
        }

        if ($image) {
            $this->deleteImage($municipality->image);
            $data['image'] = $this->saveImage($image);
        }

        return $this->municipalityRepository->update($municipality, $data);
    }

    /**
     * Eliminar un municipio
     *
     * @param Municipality $municipality Instancia del municipio a eliminar
     * @return bool
     */
    public function deleteMunicipality(int $id): bool
    {
        $municipality = $this->municipalityRepository->findById($id);
        if (!$municipality) {
            throw new Exception("El municipio con ID {$id} no existe", 404);
        }

        if ($municipality->communities()->count() > 0) {
            throw new Exception('No se puede eliminar el municipio porque tiene comunidades asociadas', 409);
        }

        return $this->municipalityRepository->delete($municipality);
    }

    /**
     * Cambia el estado de activación de un municipio.
     * Si el estado cambia, tambien se cambia el estado de sus comunidades asociados.
     *
     * @param int $id Identificador del municipio.
     * @return Municipality Municipio con el estado actualizado.
     */
    public function toggleMunicipalityStatus(int $id): Municipality
    {
        $municipality = $this->municipalityRepository->findById($id);
        if (!$municipality) {
            throw new Exception("El municipio con ID {$id} no existe", 404);
        }

        $updatedMunicipality = $this->municipalityRepository->toggleActive($municipality);

        $this->communityRepository->updateStatusByMunicipality(
            $municipality->id,
            $updatedMunicipality->is_active
        );

        return $updatedMunicipality->fresh(['communities']);
    }

    /**
     * Guardar imagen en el storage público
     *
     * @param UploadedFile $image
     * @return string Ruta de la imagen guardada
     *
     * TODO: Mover este metodo a un servicio de manejo de imágenes
     */
    private function saveImage(UploadedFile $image): string
    {
        $fileName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

        $image->move(public_path('images/municipalities'), $fileName);

        return 'images/municipalities/' . $fileName;
    }

    /**
     * Eliminar imagen del storage
     *
     * @param string $imagePath Ruta de la imagen
     * @return void
     *
     * TODO: Mover este metodo a un servicio de manejo de imágenes
     */
    private function deleteImage(string $imagePath): void
    {
        $fullPath = public_path($imagePath);

        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }
}
