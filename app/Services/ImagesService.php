<?php

namespace App\Services;

use App\Interfaces\ImagesInterface;
use App\DTOs\ImagemDto;
use App\Models\Images;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ImagesService
{
    protected ImagesInterface $imagesRepository;

    public function __construct(ImagesInterface $imagesRepository)
    {
        $this->imagesRepository = $imagesRepository;
    }

    public function getAll()
    {
        return $this->imagesRepository->getAll();
    }

    public function getAllByMateria(int $idMateria, ?array $tipos = null)
    {
        return $this->imagesRepository->getAllByMateria($idMateria, $tipos);
    }

    public function getImagesByContext(int $idMateria, string $context)
    {
        $tipos = match($context) {
            'home' => Images::TIPOS_HOME,
            'conteudo' => Images::TIPOS_CONTEUDO,
            'categoria' => Images::TIPOS_CATEGORIA,
            default => null
        };

        return $this->imagesRepository->getAllByMateria($idMateria, $tipos);
    }

    public function getById(int $id)
    {
        return $this->imagesRepository->getById($id);
    }

    public function create(int $idMateria, ImagemDto $imagesDTO)
    {
        // Valida se o tipo é válido
        if (!in_array($imagesDTO->vchr_Tipo, Images::TIPOS_VALIDOS)) {
            throw new Exception('Tipo de imagem inválido. Tipos válidos: ' . implode(', ', Images::TIPOS_VALIDOS));
        }

        return $this->imagesRepository->create($idMateria, $imagesDTO);
    }

    public function createBatch(int $idMateria, array $imagesDTOs)
    {
        // Valida todos os tipos antes de criar
        foreach ($imagesDTOs as $imageDTO) {
            if (!in_array($imageDTO->vchr_Tipo, Images::TIPOS_VALIDOS)) {
                throw new Exception('Tipo de imagem inválido: ' . $imageDTO->vchr_Tipo . '. Tipos válidos: ' . implode(', ', Images::TIPOS_VALIDOS));
            }
        }

        return $this->imagesRepository->createBatch($idMateria, $imagesDTOs);
    }

    public function update(int $id, ImagemDto $imagesDTO)
    {
        // Valida se o tipo é válido (se foi fornecido)
        if ($imagesDTO->vchr_Tipo && !in_array($imagesDTO->vchr_Tipo, Images::TIPOS_VALIDOS)) {
            throw new Exception('Tipo de imagem inválido. Tipos válidos: ' . implode(', ', Images::TIPOS_VALIDOS));
        }

        return $this->imagesRepository->update($id, $imagesDTO);
    }

    public function delete(int $id)
    {
        return $this->imagesRepository->delete($id);
    }

    public function deleteBatchByTipos(int $idMateria, array $tipos)
    {
        // Valida todos os tipos antes de deletar
        foreach ($tipos as $tipo) {
            if (!in_array($tipo, Images::TIPOS_VALIDOS)) {
                throw new Exception('Tipo de imagem inválido: ' . $tipo . '. Tipos válidos: ' . implode(', ', Images::TIPOS_VALIDOS));
            }
        }

        return $this->imagesRepository->deleteBatchByTipos($idMateria, $tipos);
    }
}
