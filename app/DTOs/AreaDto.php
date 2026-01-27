<?php

namespace App\DTOs;

class AreaDto
{
    public int $int_Id;
    public string $vchr_AreaNome;
    public ?string $vchr_Tag;
    public ?string $type;
    public bool $b_menu;
    public int $int_rolePermission;

    public function __construct(
        int $int_Id = 0,
        string $vchr_AreaNome = '',
        ?string $vchr_Tag = null,
        ?string $type = null,
        bool $b_menu = false,
        int $int_rolePermission = 0
    ) {
        $this->int_Id = $int_Id;
        $this->vchr_AreaNome = $vchr_AreaNome;
        $this->vchr_Tag = $vchr_Tag;
        $this->type = $type;
        $this->b_menu = $b_menu;
        $this->int_rolePermission = $int_rolePermission;
    }

    public function toArray(): array
    {
        return [
            'vchr_AreaNome' => $this->vchr_AreaNome,
            'vchr_Tag' => $this->vchr_Tag,
            'type' => $this->type,
            'b_menu' => $this->b_menu,
            'int_rolePermission' => $this->int_rolePermission,
        ];
    }
}

