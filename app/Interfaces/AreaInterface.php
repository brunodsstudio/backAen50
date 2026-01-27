<?php

namespace App\Interfaces;

use App\DTOs\AreaDTO;

interface AreaInterface
{
    public function getAll();
    
    public function getAllWithPaginate(int $perPage = 10, int $page = 1);

    public function getById(int $id);

    public function create(AreaDTO $userDTO);

    public function update(int $id, AreaDTO $userDTO);

    public function delete(int $id);
}