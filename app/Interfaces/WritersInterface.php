<?php

namespace App\Interfaces;

use App\DTOs\WritersDTO;

interface WritersInterface{
    /**
     * Get all writers.
     *
     * @return mixed
     */
    public function getAll();

    /**
     * Get writer by ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getById(int $id);

    /**
     * Create a new writer.
     *
     * @param array $data
     * @return mixed
     */
    public function create(WritersDTO $writersDTO);

    /**
     * Update an existing writer.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function update(int $id, WritersDTO $writersDTO);

    /**
     * Delete a writer.
     *
     * @param int $id
     * @return mixed
     */
    public function delete(int $id);
}
?>