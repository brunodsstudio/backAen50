<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoAtracao extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'aen_tipo_atracao';

    protected $fillable = [
        'nome',
    ];

    /**
     * Relacionamento: Uma TipoAtracao tem muitas Atracoes
     */
    public function atracoes()
    {
        return $this->hasMany(Atracao::class, 'tipo_atracao_id');
    }
}
