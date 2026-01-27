<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Image",
 *     type="object",
 *     title="Image",
 *     description="Modelo de Imagem de Matéria",
 *     @OA\Property(property="int_Id", type="integer", description="ID da imagem"),
 *     @OA\Property(property="vchr_ImgLink", type="string", description="URL da imagem original"),
 *     @OA\Property(property="vchr_ImgThumbLink", type="string", description="URL da thumbnail"),
 *     @OA\Property(property="int_MateriaId", type="integer", description="ID da matéria"),
 *     @OA\Property(property="vchr_Tipo", type="string", enum={"Slider_Home", "Facebook_share", "Materia_home_thumb", "Top_Materia"}, description="Tipo de imagem"),
 *     @OA\Property(property="vchr_Descricao", type="string", description="Descrição da imagem"),
 *     @OA\Property(property="dt_Upload", type="string", format="date-time", description="Data de upload"),
 *     @OA\Property(property="vchr_HRef", type="string", description="Link de referência"),
 *     @OA\Property(property="dl_SizeW", type="number", format="double", description="Largura da imagem"),
 *     @OA\Property(property="dl_SizeH", type="number", format="double", description="Altura da imagem"),
 *     @OA\Property(property="dl_Thumb_SizeW", type="number", format="double", description="Largura da thumbnail"),
 *     @OA\Property(property="dl_Thumb_SizeH", type="number", format="double", description="Altura da thumbnail"),
 *     @OA\Property(property="int_Ordem", type="integer", description="Ordem de exibição"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Data de criação"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Data de atualização")
 * )
 */
class Images extends Model
{
    protected $table = 'tb_aen_images';
    protected $primaryKey = 'int_Id';

    protected $fillable = [
        'vchr_ImgLink',
        'vchr_ImgThumbLink',
        'int_MateriaId',
        'vchr_Tipo',
        'vchr_Descricao',
        'dt_Upload',
        'vchr_HRef',
        'dl_SizeW',
        'dl_SizeH',
        'dl_Thumb_SizeW',
        'dl_Thumb_SizeH',
        'int_Ordem',
    ];

    protected $casts = [
        'dt_Upload' => 'datetime',
        'dl_SizeW' => 'double',
        'dl_SizeH' => 'double',
        'dl_Thumb_SizeW' => 'double',
        'dl_Thumb_SizeH' => 'double',
        'int_Ordem' => 'integer',
    ];

    // Tipos válidos de imagem
    public const TIPOS_VALIDOS = [
        'Slider_Home',
        'Facebook_share',
        'Materia_home_thumb',
        'Top_Materia'
    ];

    // Tipos para diferentes contextos
    public const TIPOS_HOME = ['Slider_Home', 'Facebook_share', 'Materia_home_thumb'];
    public const TIPOS_CONTEUDO = ['Facebook_share', 'Top_Materia'];
    public const TIPOS_CATEGORIA = ['Slider_Home', 'Materia_home_thumb'];

    public function materia()
    {
        return $this->belongsTo(Materia::class, 'int_MateriaId', 'id');
    }
}
