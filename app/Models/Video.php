<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Video",
 *     type="object",
 *     title="Video",
 *     description="Modelo de Vídeo do YouTube",
 *     @OA\Property(property="id", type="integer", description="ID do vídeo"),
 *     @OA\Property(property="int_IdMateria", type="integer", description="ID da matéria associada"),
 *     @OA\Property(property="vchr_VideoId", type="string", description="ID do vídeo no YouTube"),
 *     @OA\Property(property="vchr_LinkVideo", type="string", description="Link do vídeo"),
 *     @OA\Property(property="int_IdArea", type="integer", description="ID da área"),
 *     @OA\Property(property="vchr_Titulo", type="string", description="Título do vídeo"),
 *     @OA\Property(property="vchr_Description", type="string", description="Descrição do vídeo"),
 *     @OA\Property(property="vchr_YTThumbDefault", type="string", description="Thumbnail padrão do YouTube"),
 *     @OA\Property(property="vchr_YTThumbMedium", type="string", description="Thumbnail média do YouTube"),
 *     @OA\Property(property="vchr_YTThumbHigh", type="string", description="Thumbnail alta do YouTube"),
 *     @OA\Property(property="vchr_Embed", type="string", description="Código embed do vídeo"),
 *     @OA\Property(property="vchr_ChannelId", type="string", description="ID do canal do YouTube"),
 *     @OA\Property(property="vchr_tags", type="string", description="Tags do vídeo"),
 *     @OA\Property(property="dt_Publicado", type="string", format="date-time", description="Data de publicação no YouTube"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Data de criação"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Data de atualização")
 * )
 */
class Video extends Model
{
    protected $table = 'tb_aen_videos';
    protected $primaryKey = 'id';

    protected $fillable = [
        'int_IdMateria',
        'vchr_VideoId',
        'vchr_LinkVideo',
        'int_IdArea',
        'vchr_Titulo',
        'vchr_Description',
        'vchr_YTThumbDefault',
        'vchr_YTThumbMedium',
        'vchr_YTThumbHigh',
        'vchr_Embed',
        'vchr_ChannelId',
        'vchr_tags',
        'dt_Publicado'
    ];

    protected $casts = [
        'dt_Publicado' => 'datetime',
    ];

    /**
     * Relacionamento com Materia
     * Um vídeo pode estar associado a uma matéria
     */
    public function materia()
    {
        return $this->belongsTo(Materia::class, 'int_IdMateria', 'id');
    }

    /**
     * Relacionamento com Area
     * Um vídeo pertence a uma área
     */
    public function area()
    {
        return $this->belongsTo(Area::class, 'int_IdArea', 'int_Id');
    }
}
