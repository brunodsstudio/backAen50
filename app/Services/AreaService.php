<?php

namespace App\Services;

use App\Interfaces\AreaInterface;
use App\DTOs\AreaDto;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class AreaService
{
    protected AreaInterface $areaRepository;

    public function __construct(AreaInterface $areaRepository)
    {
        $this->areaRepository = $areaRepository;
    }

    public function getAll()
    {
        return $this->areaRepository->getAll();
    }

    public function getAllWithPaginate($perPage = 10, $page = 1)
    {
        return $this->areaRepository->getAllWithPaginate($perPage, $page);
    }

    public function getById(int $id)
    {
        try {
            return $this->areaRepository->getById($id);
        } catch (ModelNotFoundException $e) {
            Log::error('Area not found: ' . $e->getMessage());
            throw new Exception('Area not found.');
        } catch (Exception $e) {
            Log::error('Error retrieving area: ' . $e->getMessage());
            throw new Exception('Could not retrieve area.');
        }
    }

    public function create(AreaDto $areaDTO)
    {
        try {
            return $this->areaRepository->create($areaDTO);
        } catch (Exception $e) {
            Log::error('Error creating area: ' . $e->getMessage());
            throw new Exception('Could not create area.');
        }
    }

    public function update(int $id, AreaDto $areaDTO)
    {
        try {
            return $this->areaRepository->update($id, $areaDTO);
        } catch (ModelNotFoundException $e) {
            Log::error('Area not found: ' . $e->getMessage());
            throw new Exception('Area not found.');
        } catch (Exception $e) {
            Log::error('Error updating area: ' . $e->getMessage());
            throw new Exception('Could not update area.');
        }
    }

    public function delete(int $id)
    {
        try {
            return $this->areaRepository->delete($id);
        } catch (ModelNotFoundException $e) {
            Log::error('Area not found: ' . $e->getMessage());
            throw new Exception('Area not found.');
        } catch (Exception $e) {                
            Log::error('Error deleting area: ' . $e->getMessage());
            throw new Exception('Could not delete area.');
        }
    }
}       
