<?php

namespace App\DTOs;

class  MateriaDto
{
    public int $id;
    public ?string $dt_post;
    public ?string $vchr_autor;
    public ?int $int_autor;
    public ?string $vchr_lide;
    public ?string $vchr_titulo;
    public ?string $vchr_conteudo;
    public ?string $vchr_area;
    public ?int $id_area;
    public ?string $vchr_tags;
    public ?string $vchr_FontLink;
    public ?string $vchr_LinkTitulo;
    public ?string $vchr_seoTitle;
    public ?string $vchr_seoKeywords;
    public ?string $og_title;
    public ?string $og_description;
    public ?string $og_image;
    public ?string $vchr_s3_storage;
    public ?bool $bool_onLine;
    public ?bool $bool_home;
    public ?bool $base64Format;
    public ?string $materiaUUID;
    public ?int $IdSocialIconTemplate;
    public ?string $dt_alterado;
    public ?string $vchr_GalDir;

    public function __construct(
        int $id,
        ?string $dt_post = null,
        ?string $vchr_autor = null,
        ?int $int_autor = null,
        ?string $vchr_lide = null,
        ?string $vchr_titulo = null,
        ?string $vchr_conteudo = null,
        ?string $vchr_area = null,
        ?int $id_area = null,
        ?string $vchr_tags = null,
        ?string $vchr_FontLink = null,
        ?string $vchr_LinkTitulo = null,
        ?string $vchr_seoTitle = null,
        ?string $vchr_seoKeywords = null,
        ?string $og_title = null,
        ?string $og_description = null,
        ?string $og_image = null,
        ?string $vchr_s3_storage = null,
        ?bool $bool_onLine = null,
        ?bool $bool_home = null,
        ?bool $base64Format = null,
        ?string $materiaUUID = null,
        ?int $IdSocialIconTemplate = null,
        ?string $dt_alterado = null,
        ?string $vchr_GalDir = null
    ) {
        $this->id = $id;
        $this->dt_post = $dt_post;
        $this->vchr_autor = $vchr_autor;
        $this->int_autor = $int_autor;
        $this->vchr_lide = $vchr_lide;
        $this->vchr_titulo = $vchr_titulo;
        $this->vchr_conteudo = $vchr_conteudo;
        $this->vchr_area = $vchr_area;
        $this->id_area = $id_area;
        $this->vchr_tags = $vchr_tags;
        $this->vchr_FontLink = $vchr_FontLink;
        $this->vchr_LinkTitulo = $vchr_LinkTitulo;
        $this->vchr_seoTitle = $vchr_seoTitle;
        $this->vchr_seoKeywords = $vchr_seoKeywords;
        $this->og_title = $og_title;
        $this->og_description = $og_description;
        $this->og_image = $og_image;
        $this->vchr_s3_storage = $vchr_s3_storage;
        $this->bool_onLine = $bool_onLine;
        $this->bool_home = $bool_home;
        $this->base64Format = $base64Format;
        $this->materiaUUID = $materiaUUID;
        $this->IdSocialIconTemplate = $IdSocialIconTemplate;
        $this->dt_alterado = $dt_alterado;
        $this->vchr_GalDir = $vchr_GalDir;
    }

}
