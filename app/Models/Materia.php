<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Materia",
 *     type="object",
 *     title="Materia",
 *     description="Modelo de Matéria",
 *     @OA\Property(property="id", type="integer", description="ID da matéria"),
 *     @OA\Property(property="dt_post", type="string", format="date-time", description="Data de publicação"),
 *     @OA\Property(property="vchr_autor", type="string", description="Nome do autor"),
 *     @OA\Property(property="int_autor", type="integer", description="ID do autor"),
 *     @OA\Property(property="vchr_lide", type="string", description="Lide da matéria"),
 *     @OA\Property(property="vchr_titulo", type="string", description="Título da matéria"),
 *     @OA\Property(property="vchr_conteudo", type="string", description="Conteúdo da matéria"),
 *     @OA\Property(property="vchr_area", type="string", description="Nome da área"),
 *     @OA\Property(property="id_area", type="integer", description="ID da área"),
 *     @OA\Property(property="vchr_tags", type="string", description="Tags da matéria"),
 *     @OA\Property(property="vchr_FontLink", type="string", description="Link da fonte"),
 *     @OA\Property(property="vchr_LinkTitulo", type="string", description="Título do link"),
 *     @OA\Property(property="vchr_seoTitle", type="string", description="Título SEO"),
 *     @OA\Property(property="vchr_seoKeywords", type="string", description="Palavras-chave SEO"),
 *     @OA\Property(property="og_title", type="string", description="Título Open Graph"),
 *     @OA\Property(property="og_description", type="string", description="Descrição Open Graph"),
 *     @OA\Property(property="og_image", type="string", description="Imagem Open Graph"),
 *     @OA\Property(property="vchr_s3_storage", type="string", description="Armazenamento S3"),
 *     @OA\Property(property="bool_onLine", type="boolean", description="Se está online"),
 *     @OA\Property(property="bool_home", type="boolean", description="Se aparece na home"),
 *     @OA\Property(property="base64Format", type="string", description="Formato base64"),
 *     @OA\Property(property="materiaUUID", type="string", description="UUID da matéria"),
 *     @OA\Property(property="IdSocialIconTemplate", type="integer", description="ID do template de ícone social"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Data de criação"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Data de atualização")
 * )
 */
class Materia extends Model
{
    protected $table = 'tb_aen_materias';
    protected $primaryKey = 'id';

    protected $fillable = [
        'dt_post',
        'vchr_autor',
        'int_autor',
        'vchr_lide',
        'vchr_titulo',
        'vchr_conteudo',
        'vchr_area',
        'id_area',
        'vchr_tags',
        'vchr_FontLink',
        'vchr_LinkTitulo',
        'vchr_seoTitle',
        'vchr_seoKeywords',
        'og_title',
        'og_description',
        'og_image',
        'vchr_s3_storage',
        'bool_onLine',
        'bool_home',
        'base64Format',
        'materiaUUID',
        'IdSocialIconTemplate'
    ];


    public function Writers(){
        return $this->BelongTo(Writers::class, 'int_autor');
    }

    public function images(){
        return $this->hasMany(Images::class, 'int_MateriaId', 'id');
    }

    public function Area(){
        return $this->hasOne(Area::class, 'int_Id');
    }

}

