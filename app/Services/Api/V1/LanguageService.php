<?php

namespace App\Services\Api\V1;

use App\Models\Language;
use App\Repositories\Api\V1\LanguageRepository;
use Illuminate\Database\Eloquent\Collection;
use Exception;

class LanguageService
{
    protected LanguageRepository $languageRepository;

    public function __construct(LanguageRepository $languageRepository)
    {
        $this->languageRepository = $languageRepository;
    }

    /**
     * Obtener todos los idiomas
     *
     * @return Collection
     */
    public function getAllLanguages(): Collection
    {
        return $this->languageRepository->getAll();
    }

    /**
     * Buscar idioma por ID
     *
     * @param int $id ID del idioma
     * @return Language|null
     */
    public function findLanguageById(int $id): ?Language
    {
        return $this->languageRepository->findById($id);
    }

    /**
     * Crear un nuevo idioma
     *
     * @param array $data Datos del idioma
     * @return Language
     */
    public function createLanguage(array $data): Language
    {
        $language = $this->languageRepository->create($data);
        return $language;
    }

    /**
     * Actualizar idioma
     *
     * @param int $id ID del idioma
     * @param array $data Datos a actualizar
     * @return Language
     */
    public function updateLanguage(int $id, array $data): Language
    {
        $language = $this->languageRepository->findById($id);
        if (!$language) {
            throw new Exception('El idioma con ID ' . $id . ' no existe.', 404);
        }

        $updatedLanguage = $this->languageRepository->update($language, $data);

        return $updatedLanguage;
    }

    /**
     * Cambiar estado de un idioma
     *
     * @param int $id ID del idioma
     * @return Language
     */
    public function toggleLanguageStatus(int $id): Language
    {
        $language = $this->languageRepository->findById($id);
        if (!$language) {
            throw new Exception('El idioma con ID ' . $id . ' no existe.', 404);
        }

        if ($language->code === 'es') {
            throw new Exception('No se puede desactivar el idioma español porque es el idioma por defecto', 400);
        }

        $updatedLanguage = $this->languageRepository->toggleActive($language);
        return $updatedLanguage;
    }
}
