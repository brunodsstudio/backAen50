<?php

namespace App\DTOs;

class AreaDto
{
    public int $id;
    public string $vchr_AreaNome;
    public ?string $vchr_Tag;
    public array $type;
    public bool $b_menu;
    public int $int_rolePermission;

    public function __construct(
        int $int_Id,
        string $vchr_AreaNome,
        ?string $vchr_Tag = null,
        array $type = ['bd', 'pasta'],
        bool $b_menu = false,
        int $int_rolePermission = 0
    ) {
        $this->id = $id;
        $this->vchr_AreaNome = $vchr_AreaNome;
        $this->vchr_Tag = $vchr_Tag;
        $this->type = $type;
        $this->b_menu = $b_menu;    
        $this->int_rolePermission = $int_rolePermission;
   
    }
}

