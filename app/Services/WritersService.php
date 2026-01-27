<?php

namespace App\Services;

use App\Interfaces\WritersInterface;
use App\DTOs\WritersDto;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class WritersService
{
    protected WritersInterface $writersRepository;

    public function __construct(WritersInterface $writersRepository)
    {
        $this->writersRepository = $writersRepository;
    }

    public function getAll()
    {
        return $this->writersRepository->getAll();
    }

    public function getAllWithPaginate($perPage = 10, $page = 1)
    {
        return $this->writersRepository->getAllWithPaginate($perPage, $page);
    }

    public function getById(int $id)
    {
        return $this->writersRepository->getById($id);
    }

    public function create(WritersDto $writersDTO)
    {
        return $this->writersRepository->create($writersDTO);
    }

    public function update(int $id, WritersDto $writersDTO)
    {
        return $this->writersRepository->update($id, $writersDTO);
    }

    public function delete(int $id)
    {
        return $this->writersRepository->delete($id);
    }
}