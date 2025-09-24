<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Writers extends Model
{
    protected $table = 'tb_aen_writers';
    protected $primaryKey = 'int_Id';

    protected $fillable = [
        'vchr_Nome',
        'vchr_Nick',
        'long_Card',
        'bool_Enable',
        'vchr_LinkFoto',
        'vchr_LinkInta',    
        'vchr_Cargo'
    ];
}
