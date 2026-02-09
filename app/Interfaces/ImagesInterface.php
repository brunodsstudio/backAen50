<?php

namespace App\Interfaces;

use App\DTOs\ImagemDto;

interface ImagesInterface
{
    /**
     * Get all images.
     *
     * @return mixed
     */
    public function getAll();

    /**
     * Get all images by materia ID.
     *
     * @param int $idMateria
     * @param array|null $tipos
     * @return mixed
     */
    public function getAllByMateria(int $idMateria, ?array $tipos = null);

    /**
     * Get image by ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getById(int $id);

    /**
     * Create a new image.
     *
     * @param int $idMateria
     * @param ImagemDto $imagesDTO
     * @return mixed
     */
    public function create(int $idMateria, ImagemDto $imagesDTO);

    /**
     * Create multiple images in batch.
     *
     * @param int $idMateria
     * @param array $imagesDTOs
     * @return mixed
     */
    public function createBatch(int $idMateria, array $imagesDTOs);

    /**
     * Update an existing image.
     *
     * @param int $id
     * @param ImagemDto $imagesDTO
     * @return mixed
     */
    public function update(int $id, ImagemDto $imagesDTO);

    /**
     * Delete an image.
     *
     * @param int $id
     * @return mixed
     */
    public function delete(int $id);

    /**
     * Delete multiple images by tipos.
     *
     * @param int $idMateria
     * @param array $tipos
     * @return mixed
     */
    public function deleteBatchByTipos(int $idMateria, array $tipos);
}
