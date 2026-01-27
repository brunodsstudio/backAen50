<?php

namespace App\Interfaces;

use App\DTOs\MateriaDto;

interface MateriaInterface
{
    public function getAll();
   // public function getAllWithPaginate($perPage = 10, $page = 1);
    public function getById(int $id);
    public function getByLinkTitulo(string $linkTitulo);
    public function getByTag(string $tag, int $limit = 3);
    public function getTagsSummary(int $limit = 50);
    public function create(MateriaDto $materiaDTO);
    public function update(int $id, MateriaDto $materiaDTO);
    public function delete(int $id);
}