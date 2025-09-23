<?php

namespace App\DTOs;

class ImagemDto
{

    public int $int_Id;
    public ?string $vchr_ImgLink;
    public ?string $vchr_ImgThumbLink;
    public ?int $int_MateriaId;
    public ?string $vchr_Tipo;
    public ?string $vchr_Descricao;
    public ?string $dt_Upload;
    public ?string $vchr_HRef;
    public ?int $int_Ordem;
    

     public function __construct(
        int $int_Id,
        ?string $vchr_ImgLink = null,
        ?string $vchr_ImgThumbLink = null,
        ?int $int_MateriaId = null,
        ?string $vchr_Tipo = null,
        ?string $vchr_Descricao = null,
        ?string $dt_Upload = null,
        ?string $vchr_HRef = null,
        ?int $int_Ordem = null){
            $this->int_Id = $int_Id;
            $this->vchr_ImgLink = $vchr_ImgLink;
            $this->vchr_ImgThumbLink = $vchr_ImgThumbLink;
            $this->int_MateriaId = $int_MateriaId;
            $this->vchr_Tipo = $vchr_Tipo;
            $this->vchr_Descricao = $vchr_Descricao;
            $this->dt_Upload = $dt_Upload;
            $this->vchr_HRef = $vchr_HRef;
            $this->int_Ordem = $int_Ordem;
        }
    
}