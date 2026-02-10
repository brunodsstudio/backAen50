<?php

namespace App\Repositories;

use App\Interfaces\WritersInterface;
use App\DTOs\WritersDto;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use App\Models\Writers;

class WritersRepository implements WritersInterface 
{
    public function getAll()
    {
        return Writers::orderBy('vchr_Nome', 'asc')->get();
    }

    public function getAllWithPaginate(int $perPage = 10, int $page = 1)
    {
        return Writers::select(
                'int_Id',
                'vchr_Nome',
                'vchr_Nick',
                'long_Card',
                'bool_Enable',
                'vchr_LinkFoto',
                'vchr_LinkInta',
                'vchr_Cargo',
                'created_at',
                'updated_at'
            )
            ->orderBy('vchr_Nome', 'asc')
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function getById(int $id)
    {
        return Writers::findOrFail($id);
    }

    public function create(WritersDto $writersDTO)
    {
        return Writers::create($writersDTO->toArray());
    }

    public function update(int $id, WritersDto $writersDTO)
    {
        $writer = Writers::findOrFail($id);
        $writer->update($writersDTO->toArray());
        return $writer->fresh();
    }

    public function delete(int $id)
    {
        $writer = Writers::findOrFail($id);
        return $writer->delete();
    }
}   