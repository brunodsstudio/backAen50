<?php

namespace App\Interfaces;

use App\DTOs\MateriaDTO;

interface MateriaInterface{
    /**
     * Get all materias.
     *
     * @return mixed
     */
    public function getAll();

    /**
     * Get materia by ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getById(int $id);

    /**
     * Create a new materia.
     *
     * @param array $data
     * @return mixed
     */
    public function create(MateriaDTO $materiaDTO);

    /**
     * Update an existing materia.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(int $id, MateriaDTO $materiaDTO);

    /**
     * Delete a materia.
     *
     * @param int $id
     * @return mixed
     */
    public function delete(int $id);
}
