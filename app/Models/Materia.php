<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
     protected $table = 'tb_aen_materia';
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

    public function images()
    {
        return $this->hasMany(Images::class, 'int_MateriaId');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'id_area');
    }

    public function writer()
    {
        return $this->belongsTo(Writers::class, 'int_autor');
    }
}
