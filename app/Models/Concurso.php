<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Concurso extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'aen_concursos';

    protected $fillable = [
        'nome',
        'tipo_concurso_id',
        'link_foto',
        'descricao',
        'datas_horas',
    ];

    /**
     * Relacionamento: Um Concurso pertence a um TipoConcurso
     */
    public function tipoConcurso()
    {
        return $this->belongsTo(TipoConcurso::class, 'tipo_concurso_id');
    }

    /**
     * Relacionamento: Um Concurso pode estar em muitos Eventos (many-to-many)
     */
    public function eventos()
    {
        return $this->belongsToMany(Evento::class, 'aen_evento_concurso', 'concurso_id', 'evento_id')
                    ->withTimestamps()
                    ->withPivot('deleted_at');
    }
}
