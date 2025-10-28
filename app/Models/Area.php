<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class area extends Model
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
