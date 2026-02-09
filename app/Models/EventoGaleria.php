<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventoGaleria extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'aen_evento_galeria';

    protected $fillable = [
        'evento_id',
        'link_materia',
        'dia',
        'descricao',
        'pasta_aws',
    ];

    protected $casts = [
        'dia' => 'date',
    ];

    /**
     * Relacionamento: Uma Galeria pertence a um Evento
     */
    public function evento()
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }
}
