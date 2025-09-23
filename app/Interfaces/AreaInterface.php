<?php

namespace App\Interfaces;

use App\DTOs\AreaDTO;

interface AreaInterface
{
    /**
     * Get all areas.
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
    public function create(AreaDTO $userDTO);

    /**
     * Update an existing area.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(int $id, AreaDTO $userDTO);

    /**
     * Delete an area.
     *
     * @param int $id
     * @return mixed
     */
    public function delete(int $id);
}