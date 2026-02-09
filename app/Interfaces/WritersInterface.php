<?php

namespace App\Interfaces;

use App\DTOs\WritersDto;

interface WritersInterface
{
    public function getAll();
    
    public function getAllWithPaginate(int $perPage = 10, int $page = 1);
    
    public function getById(int $id);
    
    public function create(WritersDto $writersDTO);
    
    public function update(int $id, WritersDto $writersDTO);
    
    public function delete(int $id);
}
