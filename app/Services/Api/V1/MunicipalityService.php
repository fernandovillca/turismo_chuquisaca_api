<?php

namespace App\Services\Api\V1;

use App\Repositories\Api\V1\MunicipalityRepository;
use App\Repositories\Api\V1\RegionRepository;
use App\Models\Municipality;
use App\Exceptions\ModelNotFoundApiException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Exception;

class MunicipalityService
{
    protected MunicipalityRepository $municipalityRepository;
    protected RegionRepository $regionRepository;

    public function __construct(
        MunicipalityRepository $municipalityRepository,
        RegionRepository $regionRepository
    ) {
        $this->municipalityRepository = $municipalityRepository;
        $this->regionRepository = $regionRepository;
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
     * Obtener municipios activos de una región específica
     * Valida que la región exista antes de buscar sus municipios
     *
     * @param int $regionId ID de la región
     * @return Collection
     * @throws Exception Si la región no existe
     */
    public function getActiveMunicipalitiesByRegion(int $regionId): Collection
    {
        // Validar que la región exista
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
        return $this->municipalityRepository->findById($id);
    }

    /**TODO: OK
     * Crear un nuevo municipio
     *
     * @param array $data Datos del municipio
     * @return Municipality
     * @throws Exception Si hay errores de validación de negocio
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

        $data['is_active'] = $data['is_active'] ?? true;

        return $this->municipalityRepository->create($data);
    }

    /**
     * Guardar imagen en el storage público
     *
     * @param UploadedFile $image
     * @return string Ruta de la imagen guardada
     */
    private function saveImage(UploadedFile $image): string
    {
        $fileName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

        $image->move(public_path('images/municipalities'), $fileName);

        return 'images/municipalities/' . $fileName;
    }

    /**
     * Actualizar un municipio existente
     *
     * @param Municipality $municipality Instancia del municipio a actualizar
     * @param array $data Datos a actualizar
     * @return Municipality
     * @throws Exception Si hay errores de validación de negocio
     */
    public function updateMunicipality(Municipality $municipality, array $data, ?UploadedFile $image = null): Municipality
    {
        if (isset($data['region_id']) && $data['region_id'] != $municipality->region_id) {
            $region = $this->regionRepository->findById($data['region_id']);

            if (!$region) {
                throw new Exception("La región con ID {$data['region_id']} no existe");
            }

            if (!$region->is_active) {
                throw new Exception("No se puede asignar un municipio a una región inactiva");
            }
        }

        if ($image) {
            $this->deleteImage($municipality->image);

            $data['image'] = $this->saveImage($image);
        }

        return $this->municipalityRepository->update($municipality, $data);
    }


    /**
     * Obtener un municipio por ID o lanzar excepción si no existe
     *
     * @param int $id ID del municipio
     * @return Municipality
     * @throws ModelNotFoundApiException Si el municipio no existe
     */
    public function getMunicipalityByIdOrFail(int $id): Municipality
    {
        $municipality = $this->municipalityRepository->findById($id);

        if (!$municipality) {
            throw new ModelNotFoundApiException("El municipio con ID {$id} no existe");
        }

        return $municipality;
    }

    /**
     * Eliminar imagen del storage
     *
     * @param string $imagePath Ruta de la imagen
     * @return void
     */
    private function deleteImage(string $imagePath): void
    {
        $fullPath = public_path($imagePath);

        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }

    /**
     * Eliminar un municipio
     * Puede incluir validaciones adicionales antes de eliminar
     *
     * @param Municipality $municipality Instancia del municipio a eliminar
     * @return bool
     * @throws Exception Si no se puede eliminar por reglas de negocio
     */
    public function deleteMunicipality(Municipality $municipality): bool
    {
        // Aquí puedes agregar validaciones adicionales
        // Por ejemplo: verificar que no tenga lugares turísticos asociados

        return $this->municipalityRepository->delete($municipality);
    }


    /**
     * Actualizar la imagen de un municipio
     * Maneja la lógica de negocio para el cambio de imagen
     *
     * @param Municipality $municipality Instancia del municipio
     * @param string $imagePath Ruta de la nueva imagen
     * @return Municipality
     */
    public function updateMunicipalityImage(Municipality $municipality, string $imagePath): Municipality
    {
        // Aquí puedes agregar lógica adicional como:
        // - Eliminar la imagen anterior del storage
        // - Validar que la imagen exista
        // - Optimizar la imagen antes de guardar

        return $this->municipalityRepository->updateImage($municipality, $imagePath);
    }

    /**
     * Validar que un municipio pertenezca a una región específica
     *
     * @param Municipality $municipality Instancia del municipio
     * @param int $regionId ID de la región esperada
     * @return bool
     */
    public function municipalityBelongsToRegion(Municipality $municipality, int $regionId): bool
    {
        return $municipality->region_id === $regionId;
    }
}
