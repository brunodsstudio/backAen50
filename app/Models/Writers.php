<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

/**
 * @OA\Schema(
 *     schema="Writer",
 *     type="object",
 *     title="Writer",
 *     description="Modelo de escritor/autor",
 *     @OA\Property(property="int_Id", type="integer", description="ID do escritor"),
 *     @OA\Property(property="vchr_Nome", type="string", maxLength=100, description="Nome completo"),
 *     @OA\Property(property="vchr_Nick", type="string", maxLength=50, description="Apelido/Nick"),
 *     @OA\Property(property="long_Card", type="string", description="Biografia/Card do escritor"),
 *     @OA\Property(property="bool_Enable", type="boolean", description="Habilitado"),
 *     @OA\Property(property="vchr_LinkFoto", type="string", maxLength=50, description="Link da foto"),
 *     @OA\Property(property="vchr_LinkInta", type="string", maxLength=50, description="Link Instagram"),
 *     @OA\Property(property="vchr_Cargo", type="string", maxLength=50, description="Cargo/Função"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Writers extends Model
{
    protected $table = 'tb_aen_writers';
    protected $primaryKey = 'int_Id';
    public $timestamps = true;

    protected $fillable = [
        'vchr_Nome',
        'vchr_Nick',
        'long_Card',
        'bool_Enable',
        'vchr_LinkFoto',
        'vchr_LinkInta',
        'vchr_Cargo',
    ];

    protected $casts = [
        'bool_Enable' => 'boolean',
    ];

    public function materias()
    {
        return $this->hasMany(Materia::class, 'int_autor', 'int_Id');
    }
}
