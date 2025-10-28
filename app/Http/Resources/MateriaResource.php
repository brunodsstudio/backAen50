<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MateriaResource extends JsonResource
{
    public function toArray($request)
    {
       // dd($this);
        return [
            'id' => $this->id,
            'title' => $this->vchr_titulo,
            'content' => $this->vchr_conteudo,
            'area' => $this->vchr_area,
            'vchr_titulo' => $this->vchr_titulo,
            'area_id' => $this->id_area,
            'link_titulo' => $this->vchr_LinkTitulo,
            'on_line' => $this->bool_onLine,
            'home' => $this->bool_home,
            'created_at' => $this->dt_post,
            'updated_at' => $this->dt_alterado,
            'author' => $this->vchr_autor,
            'author_id' => $this->int_autor,
            'tags' => $this->vchr_tags,
            'font_link' => $this->vchr_FontLink,
            'social_icon_template' => $this->IdSocialIconTemplate,
            'gal_dir' => $this->vchr_GalDir,
            'og_url' => $this->og_url,
            'og_type' => $this->og_type,
            'og_title' => $this->og_title,
            'og_description' => $this->og_description,
            'og_image' => $this->og_image,
            's3_storage' => $this->vchr_s3_storage,
            'materia_uuid' => $this->materiaUUID,
            'base64_format' => $this->base64Format,
            'social_icon_template' => $this->IdSocialIconTemplate,
            'lide' => $this->vchr_lide,
            'seo_title' => $this->vchr_seoTitle,
            'seo_keywords' => $this->vchr_seoKeywords,
        ];
    }
}

