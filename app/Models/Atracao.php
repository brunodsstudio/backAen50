<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Atracao extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'aen_atracoes';

    protected $fillable = [
        'nome',
        'tipo_atracao_id',
        'link_foto',
        'link_instagram',
        'link_perfil',
        'descricao',
    ];

    /**
     * Relacionamento: Uma Atracao pertence a um TipoAtracao
     */
    public function tipoAtracao()
    {
        return $this->belongsTo(TipoAtracao::class, 'tipo_atracao_id');
    }

    /**
     * Relacionamento: Uma Atracao pode estar em muitos Eventos (many-to-many)
     */
    public function eventos()
    {
        return $this->belongsToMany(Evento::class, 'aen_evento_atracao', 'atracao_id', 'evento_id')
                    ->withTimestamps()
                    ->withPivot('deleted_at');
    }
}
