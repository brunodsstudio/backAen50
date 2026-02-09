<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evento extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'aen_eventos';

    protected $fillable = [
        'nome',
        'descricao',
        'realizacao',
        'link_foto',
        'link_logo',
        'link_site',
        'link_instagram',
        'link_video',
        'link_x',
        'link_tiktok',
    ];

    protected $casts = [
        'realizacao' => 'datetime',
    ];

    /**
     * Relacionamento: Um Evento tem muitas Agendas
     */
    public function agendas()
    {
        return $this->hasMany(AgendaEvento::class, 'evento_id');
    }

    /**
     * Relacionamento: Um Evento tem muitas Galerias
     */
    public function galerias()
    {
        return $this->hasMany(EventoGaleria::class, 'evento_id');
    }

    /**
     * Relacionamento: Um Evento tem muitas Atracoes (many-to-many)
     */
    public function atracoes()
    {
        return $this->belongsToMany(Atracao::class, 'aen_evento_atracao', 'evento_id', 'atracao_id')
                    ->withTimestamps()
                    ->withPivot('deleted_at');
    }

    /**
     * Relacionamento: Um Evento tem muitos Concursos (many-to-many)
     */
    public function concursos()
    {
        return $this->belongsToMany(Concurso::class, 'aen_evento_concurso', 'evento_id', 'concurso_id')
                    ->withTimestamps()
                    ->withPivot('deleted_at');
    }
}
