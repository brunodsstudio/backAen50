<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Images extends Model
{
    protected $table = 'tb_aen_images';
    protected $primaryKey = 'id';

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

    public function materia()
    {
        return $this->belongsTo(Materia::class, 'int_MateriaId');
    }   
}
