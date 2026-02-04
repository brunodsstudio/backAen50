<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoConcurso extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'aen_tipo_concurso';

    protected $fillable = [
        'nome',
    ];

    /**
     * Relacionamento: Uma TipoConcurso tem muitos Concursos
     */
    public function concursos()
    {
        return $this->hasMany(Concurso::class, 'tipo_concurso_id');
    }
}
