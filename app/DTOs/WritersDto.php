<?php

namespace App\DTOs;

class WritersDto
{
    public int $int_Id;
    public ?string $vchr_Nome;
    public ?string $vchr_Nick;
    public ?int $long_Card;
    public ?bool $bool_Enable;

    public function __construct(
        int $int_Id,
        ?string $vchr_Nome = null,
        ?string $vchr_Nick = null,
        ?int $long_Card = null,
        ?bool $bool_Enable = null
    ) {
        $this->int_Id = $int_Id;
        $this->vchr_Nome = $vchr_Nome;
        $this->vchr_Nick = $vchr_Nick;
        $this->long_Card = $long_Card;
        $this->bool_Enable = $bool_Enable;
    }
}
