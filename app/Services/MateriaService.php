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
    public function getAllWithPaginate($perPage = 10, $page = 1, $search = '')
    {
        return $this->materiaRepository->getAllWithPaginate($perPage, $page, $search);
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

    public function getMateriasHome(int $limit = 20)
    {
        return $this->materiaRepository->getMateriasHome($limit);
    }

    public function getMateriasCategoria(
        int $idArea,
        int $perPage = 10,
        string $orderBy = 'created_at',
        string $orderDirection = 'desc'
    ) {
        return $this->materiaRepository->getMateriasCategoria(
            $idArea,
            $perPage,
            $orderBy,
            $orderDirection
        );
    }

    public function getByLinkTitulo(string $linkTitulo)
    {
        return $this->materiaRepository->getByLinkTitulo($linkTitulo);
    }

    public function getByTag(string $tag, int $limit = 3)
    {
        return $this->materiaRepository->getByTag($tag, $limit);
    }

    public function getTagsSummary(int $limit = 50)
    {
        return $this->materiaRepository->getTagsSummary($limit);
    }
}
