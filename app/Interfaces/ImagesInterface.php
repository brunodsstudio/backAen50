<?php

namespace App\Interfaces;

use App\DTOs\ImagesDTO;

interface ImagesInterface{
    /**
     * Get all images.
     *
     * @return mixed
     */
    public function getAll();

    /**
     * Get area by ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getById(int $id);

    /**
     * Create a new area.
     *
     * @param array $data
     * @return mixed
     */
    public function create(ImagesDTO $imagesrDTO);

    /**
     * Update an existing area.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(int $id, ImagesDTO $imagesDTO);

    /**
     * Delete an area.
     *
     * @param int $id
     * @return mixed
     */
    public function delete(int $id);
}
