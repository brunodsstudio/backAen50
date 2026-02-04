<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgendaEvento extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'aen_agenda_evento';

    protected $fillable = [
        'evento_id',
        'data',
        'endereco',
        'cidade',
    ];

    protected $casts = [
        'data' => 'date',
    ];

    /**
     * Relacionamento: Uma Agenda pertence a um Evento
     */
    public function evento()
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }
}
