<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Images extends Model
{
    protected $table = 'tb_aen_images';
    protected $primaryKey = 'int_Id';

    protected $fillable = [
        'vchr_ImgLink',
        'vchr_ImgThumbLink',
        'int_MateriaId',
    ];

    public function Materia(){
        return $this->belongsTo(Materia::class, 'int_MateriaId');
    }   

}
