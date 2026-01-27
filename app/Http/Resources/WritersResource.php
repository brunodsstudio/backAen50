<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WritersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->int_Id,
            'nome' => $this->vchr_Nome,
            'nick' => $this->vchr_Nick,
            'biografia' => $this->long_Card,
            'habilitado' => $this->bool_Enable,
            'foto' => $this->vchr_LinkFoto,
            'instagram' => $this->vchr_LinkInta,
            'cargo' => $this->vchr_Cargo,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
