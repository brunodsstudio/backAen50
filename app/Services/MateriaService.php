<?php

namespace App\Services;

use App\Interfaces\MateriaInterface;
use App\DTOs\MateriaDto;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class MateriaService
{
    protected MateriaInterface $materiaRepository;

    public function __construct(MateriaInterface $materiaRepository)
    {
        $this->materiaRepository = $materiaRepository;
    }

    public function getAll()
    {
        return $this->materiaRepository->getAll();
    }
    public function getAllWithPaginate($perPage = 10, $page = 1)
    {
        return $this->materiaRepository->getAllWithPaginate($perPage, $page);
    }

    public function getById(int $id)
    {
        return $this->materiaRepository->getById($id);
    }

    public function create(MateriaDto $materiaDTO)
    {
        return $this->materiaRepository->create($materiaDTO);
    }

    public function update(int $id, MateriaDto $materiaDTO)
    {
        return $this->materiaRepository->update($id, $materiaDTO);
    }

    public function delete(int $id)
    {
        return $this->materiaRepository->delete($id);
    }
}