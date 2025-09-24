<?php

namespace App\DTOs;

class WritersDto
{
public $vchr_Nome;
public $vchr_Nick;
public $long_Card;
public $bool_Enable;
public $vchr_LinkFoto;
public $vchr_LinkInta;
public $vchr_Cargo;

public function __construct(
    $vchr_Nome, 
    $vchr_Nick, 
    $long_Card, 
    $bool_Enable, 
    $vchr_LinkFoto, 
    $vchr_LinkInta, 
    $vchr_Cargo) {
    $this->vchr_Nome = $vchr_Nome;
    $this->vchr_Nick = $vchr_Nick;
    $this->long_Card = $long_Card;
    $this->bool_Enable = $bool_Enable;
    $this->vchr_LinkFoto = $vchr_LinkFoto;
    $this->vchr_LinkInta = $vchr_LinkInta;
    $this->vchr_Cargo = $vchr_Cargo;    
}
}
?>