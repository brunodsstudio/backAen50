<?php

namespace App\Repositories;

use App\Interfaces\MateriaInterface;
use App\DTOs\MateriaDto;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use App\Models\Materia;

class MateriaRepository implements MateriaInterface
{
    public function getAll()
    {
        return Materia::all();
    }
    public function getAllWithPaginate(int $perPage = 10, int $page = 1)
    {
      //$query = Materia::select("select count(*) from tb_aen_materias");
      //$all = [];

      return Materia::select(
          'id',
            'dt_post', 
            'vchr_autor', 
            'vchr_titulo', 
            'vchr_area', 
            'id_area', 
            'vchr_LinkTitulo',  
            'bool_onLine', 
            'bool_home')
        ->orderBy('dt_post', 'desc')
        ->paginate(20); //, ['*'], 'page', 1);

    }   


    public function getById(int $id)
    {
        return Materia::find($id);
    }

    public function create(MateriaDto $materiaDTO)
    {
        return Materia::create($materiaDTO);
    }

    public function update(int $id, MateriaDto $materiaDTO)
    {
        return Materia::find($id)->update($materiaDTO);
    }

    public function delete(int $id)
    {
        return Materia::find($id)->delete();
    }
}