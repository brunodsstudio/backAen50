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
            'int_Id' => $this->int_Id,
            'vchr_Nome' => $this->vchr_Nome,
            'vchr_Nick' => $this->vchr_Nick,
            'long_Card' => $this->long_Card,
            'bool_Enable' => $this->bool_Enable,
            'vchr_LinkFoto' => $this->vchr_LinkFoto,
            'vchr_LinkInta' => $this->vchr_LinkInta,
            'vchr_Cargo' => $this->vchr_Cargo,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
