<?php

namespace App\DTOs;

class WritersDto
{
    public int $int_Id;
    public string $vchr_Nome;
    public string $vchr_Nick;
    public ?string $long_Card;
    public ?bool $bool_Enable;
    public ?string $vchr_LinkFoto;
    public ?string $vchr_LinkInta;
    public ?string $vchr_Cargo;

    public function __construct(
        int $int_Id = 0,
        string $vchr_Nome = '',
        string $vchr_Nick = '',
        ?string $long_Card = null,
        ?bool $bool_Enable = true,
        ?string $vchr_LinkFoto = null,
        ?string $vchr_LinkInta = null,
        ?string $vchr_Cargo = null
    ) {
        $this->int_Id = $int_Id;
        $this->vchr_Nome = $vchr_Nome;
        $this->vchr_Nick = $vchr_Nick;
        $this->long_Card = $long_Card;
        $this->bool_Enable = $bool_Enable;
        $this->vchr_LinkFoto = $vchr_LinkFoto;
        $this->vchr_LinkInta = $vchr_LinkInta;
        $this->vchr_Cargo = $vchr_Cargo;
    }

    public function toArray(): array
    {
        return [
            'vchr_Nome' => $this->vchr_Nome,
            'vchr_Nick' => $this->vchr_Nick,
            'long_Card' => $this->long_Card,
            'bool_Enable' => $this->bool_Enable,
            'vchr_LinkFoto' => $this->vchr_LinkFoto,
            'vchr_LinkInta' => $this->vchr_LinkInta,
            'vchr_Cargo' => $this->vchr_Cargo,
        ];
    }
}
