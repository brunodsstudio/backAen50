<?php

namespace App\Repositories;

use App\Interfaces\AreaInterface;
use App\Models\Area;
use App\DTOs\AreaDto;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class AreaRepository implements AreaInterface
{
    protected Area $model;

    public function __construct(Area $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->orderBy('vchr_AreaNome', 'asc')->get();
    }

    public function getAllWithPaginate(int $perPage = 10, int $page = 1)
    {
        return $this->model
            ->select('int_Id', 'vchr_AreaNome', 'vchr_Tag', 'type', 'b_menu', 'int_rolePermission')
            ->orderBy('vchr_AreaNome', 'asc')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function getById(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(AreaDto $areaDTO)
    {
        try {
            return $this->model->create($areaDTO->toArray());
        } catch (Exception $e) {
            Log::error('Error creating area: ' . $e->getMessage());
            throw new Exception('Could not create area.');
        }
    }

    public function update(int $id, AreaDto $areaDTO)
    {
        try {
            $area = $this->model->findOrFail($id);
            $area->update($areaDTO->toArray());
            return $area->fresh();
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
            $area = $this->model->findOrFail($id);
            return $area->delete();
        } catch (ModelNotFoundException $e) {
            Log::error('Area not found: ' . $e->getMessage());
            throw new Exception('Area not found.');
        } catch (Exception $e) {
            Log::error('Error deleting area: ' . $e->getMessage());
            throw new Exception('Could not delete area.');
        }  

    }
}
