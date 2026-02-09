<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Area",
 *     type="object",
 *     title="Area",
 *     description="Modelo de Área",
 *     @OA\Property(property="int_Id", type="integer", description="ID da área"),
 *     @OA\Property(property="vchr_AreaNome", type="string", description="Nome da área"),
 *     @OA\Property(property="vchr_Tag", type="string", description="Tag da área"),
 *     @OA\Property(property="type", type="array", @OA\Items(type="string"), description="Tipos da área"),
 *     @OA\Property(property="b_menu", type="boolean", description="Se aparece no menu"),
 *     @OA\Property(property="int_rolePermission", type="integer", description="Permissão de role"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Data de criação"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Data de atualização")
 * )
 */
class Area extends Model
{
    protected $table = 'tb_aen_area';
    protected $primaryKey = 'int_Id';

    protected $fillable = [
        'vchr_AreaNome',
        'vchr_Tag',
        'type',
        'b_menu',
        'int_rolePermission'
    ];

    public function Materia(){
        return $this->hasMany(Materia::class, 'id_area');
    }

    //
}
