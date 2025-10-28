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
        return Writers::all();
    }

    public function getById(int $id)
    {
        return Writers::find($id);
    }

    public function create(WritersDto $writersDTO)
    {
        return Writers::create($writersDTO);
    }

    public function update(int $id, WritersDto $writersDTO)
    {
        return Writers::find($id)->update($writersDTO);
    }

    public function delete(int $id)
    {
        return Writers::find($id)->delete();
    }
}   