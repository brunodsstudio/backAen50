<?php

namespace App\Repositories;

use App\Interfaces\MateriaInterface;
use App\DTOs\MateriaDto;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use App\Models\Materia;
use Illuminate\Support\Facades\Schema;

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
        ->orderBy('id', 'desc')
        ->paginate(20); //, ['*'], 'page', 1);

    }   


    public function getById(int $id)
    {
        return Materia::find($id);
    }

    public function create(MateriaDto $materiaDTO)
    {
        $attributes = $this->filterAttributes($materiaDTO->toArray());
        return Materia::create($attributes);
    }

    public function update(int $id, MateriaDto $materiaDTO)
    {
        $materia = Materia::findOrFail($id);
        $attributes = $this->filterAttributes($materiaDTO->toArray());
        $materia->fill($attributes);
        $materia->save();

        return $materia;
    }

    private function filterAttributes(array $attributes): array
    {
        $table = (new Materia())->getTable();
        $columns = Schema::getColumnListing($table);

        return array_intersect_key($attributes, array_flip($columns));
    }

    public function delete(int $id)
    {
        return Materia::find($id)->delete();
    }

    public function getMateriasHome(int $limit = 20)
    {
        return Materia::select('id',
            'dt_post', 
            'vchr_lide',
            'vchr_autor', 
            'vchr_titulo', 
            'vchr_area', 
            'id_area', 
            'vchr_LinkTitulo',  
            'bool_onLine', 
            'bool_home')
            ->with(['images' => function($query) {
                $query->select('int_Id', 'int_MateriaId', 'vchr_Tipo', 'vchr_HRef');
            }])
            ->where('bool_home', true)
            ->where('bool_onLine', true)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getMateriasCategoria(
        int $idArea,
        int $perPage = 10,
        string $orderBy = 'created_at',
        string $orderDirection = 'desc'
    ) 
    {
        $query = Materia::select(
            'id',
            'dt_post',
            'vchr_autor',
            'int_autor',
            'vchr_titulo',
            'vchr_lide',
            'vchr_area',
            'id_area',
            'vchr_tags',
            'vchr_LinkTitulo',
            'bool_onLine',
            'bool_home',
            'created_at',
            'updated_at'
        )
        ->with(['images' => function($query) {
            $query->select('int_Id', 'int_MateriaId', 'vchr_Tipo', 'vchr_HRef')
                  ->where('vchr_Tipo', 'Top_Materia');
        }])
        ->where('id_area', $idArea)
        ->where('bool_onLine', true);

        // Validar campo de ordenação
        $allowedOrderFields = ['created_at', 'dt_post', 'vchr_titulo', 'vchr_autor'];
        if (in_array($orderBy, $allowedOrderFields)) {
            $query->orderBy($orderBy, $orderDirection);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query->paginate($perPage);
    }

    public function getByLinkTitulo(string $linkTitulo)
    {
        return Materia::where('vchr_LinkTitulo', $linkTitulo)
            ->with(['images'])
            ->first();
    }

    public function getByTag(string $tag, int $limit = 3)
    {
        return Materia::select(
            'id',
            'dt_post',
            'vchr_autor',
            'int_autor',
            'vchr_titulo',
            'vchr_lide',
            'vchr_area',
            'id_area',
            'vchr_LinkTitulo',
            'created_at'
        )
        ->with(['images' => function($query) {
            $query->select('int_Id', 'int_MateriaId', 'vchr_Tipo', 'vchr_HRef')
                  ->where('vchr_Tipo', 'Top_Materia');
        }])
        ->where('vchr_tags', 'LIKE', '%' . $tag . '%')
        ->where('bool_onLine', true)
        ->orderBy('created_at', 'desc')
        ->limit($limit)
        ->get();
    }

    public function getTagsSummary(int $limit = 50)
    {
        // Buscar as últimas matérias com tags
        $materias = Materia::select('vchr_tags', 'created_at')
            ->whereNotNull('vchr_tags')
            ->where('vchr_tags', '!=', '')
            ->where('bool_onLine', true)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        $tagsCount = [];

        foreach ($materias as $materia) {
            if (!empty($materia->vchr_tags)) {
                // Separar tags por ";" ou ","
                $tags = preg_split('/[;,]/', $materia->vchr_tags);
                
                foreach ($tags as $tag) {
                    $tag = trim($tag);
                    if (!empty($tag)) {
                        if (!isset($tagsCount[$tag])) {
                            $tagsCount[$tag] = 0;
                        }
                        $tagsCount[$tag]++;
                    }
                }
            }
        }

        // Ordenar por uso (mais usadas primeiro)
        arsort($tagsCount);

        // Converter para array de objetos
        $result = [];
        foreach ($tagsCount as $tag => $count) {
            $result[] = [
                'tag' => $tag,
                'count' => $count
            ];
        }

        return $result;
    }
}
