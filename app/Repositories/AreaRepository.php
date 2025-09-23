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
        return $this->model->all();
    }

    public function getById(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(AreaDto $areaDTO)
    {
        try {
            $area = new Area();
            $area->vchr_AreaNome = $areaDTO->vchr_AreaNome;
            $area->vchr_Tag = $areaDTO->vchr_Tag;
            $area->type = json_encode($areaDTO->type);
            $area->b_menu = $areaDTO->b_menu;
            $area->int_rolePermission = $areaDTO->int_rolePermission;
            $area->save();

            return $area;
        } catch (Exception $e) {
            Log::error('Error creating area: ' . $e->getMessage());
            throw new Exception('Could not create area.');
        }
    }

    public function update(int $id, AreaDto $areaDTO)
    {
        try {
            $area = $this->model->findOrFail($id);
            $area->vchr_AreaNome = $areaDTO->vchr_AreaNome;
            $area->vchr_Tag = $areaDTO->vchr_Tag;
            $area->type = json_encode($areaDTO->type);
            $area->b_menu = $areaDTO->b_menu;
            $area->int_rolePermission = $areaDTO->int_rolePermission;
            $area->save();

            return $area;
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
