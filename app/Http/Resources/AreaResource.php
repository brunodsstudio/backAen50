<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AreaResource extends JsonResource
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
            'nome' => $this->vchr_AreaNome,
            'tag' => $this->vchr_Tag,
            'type' => $this->type,
            'menu' => $this->b_menu,
            'role_permission' => $this->int_rolePermission,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
