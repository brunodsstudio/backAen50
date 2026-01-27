<?php

namespace App\DTOs;

class VideoDto
{
    public int $id;
    public ?int $int_IdMateria;
    public ?string $vchr_VideoId;
    public ?string $vchr_LinkVideo;
    public ?int $int_IdArea;
    public string $vchr_Titulo;
    public string $vchr_Description;
    public string $vchr_YTThumbDefault;
    public string $vchr_YTThumbMedium;
    public string $vchr_YTThumbHigh;
    public string $vchr_Embed;
    public string $vchr_ChannelId;
    public string $vchr_tags;
    public ?string $dt_Publicado;

    public function __construct(
        int $id,
        string $vchr_Titulo,
        string $vchr_Description,
        string $vchr_YTThumbDefault,
        string $vchr_YTThumbMedium,
        string $vchr_YTThumbHigh,
        string $vchr_Embed,
        string $vchr_ChannelId,
        string $vchr_tags,
        ?int $int_IdMateria = null,
        ?string $vchr_VideoId = null,
        ?string $vchr_LinkVideo = null,
        ?int $int_IdArea = null,
        ?string $dt_Publicado = null
    ) {
        $this->id = $id;
        $this->int_IdMateria = $int_IdMateria;
        $this->vchr_VideoId = $vchr_VideoId;
        $this->vchr_LinkVideo = $vchr_LinkVideo;
        $this->int_IdArea = $int_IdArea;
        $this->vchr_Titulo = $vchr_Titulo;
        $this->vchr_Description = $vchr_Description;
        $this->vchr_YTThumbDefault = $vchr_YTThumbDefault;
        $this->vchr_YTThumbMedium = $vchr_YTThumbMedium;
        $this->vchr_YTThumbHigh = $vchr_YTThumbHigh;
        $this->vchr_Embed = $vchr_Embed;
        $this->vchr_ChannelId = $vchr_ChannelId;
        $this->vchr_tags = $vchr_tags;
        $this->dt_Publicado = $dt_Publicado;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? 0,
            vchr_Titulo: $data['vchr_Titulo'] ?? '',
            vchr_Description: $data['vchr_Description'] ?? '',
            vchr_YTThumbDefault: $data['vchr_YTThumbDefault'] ?? '',
            vchr_YTThumbMedium: $data['vchr_YTThumbMedium'] ?? '',
            vchr_YTThumbHigh: $data['vchr_YTThumbHigh'] ?? '',
            vchr_Embed: $data['vchr_Embed'] ?? '',
            vchr_ChannelId: $data['vchr_ChannelId'] ?? '',
            vchr_tags: $data['vchr_tags'] ?? '',
            int_IdMateria: $data['int_IdMateria'] ?? null,
            vchr_VideoId: $data['vchr_VideoId'] ?? null,
            vchr_LinkVideo: $data['vchr_LinkVideo'] ?? null,
            int_IdArea: $data['int_IdArea'] ?? null,
            dt_Publicado: $data['dt_Publicado'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'int_IdMateria' => $this->int_IdMateria,
            'vchr_VideoId' => $this->vchr_VideoId,
            'vchr_LinkVideo' => $this->vchr_LinkVideo,
            'int_IdArea' => $this->int_IdArea,
            'vchr_Titulo' => $this->vchr_Titulo,
            'vchr_Description' => $this->vchr_Description,
            'vchr_YTThumbDefault' => $this->vchr_YTThumbDefault,
            'vchr_YTThumbMedium' => $this->vchr_YTThumbMedium,
            'vchr_YTThumbHigh' => $this->vchr_YTThumbHigh,
            'vchr_Embed' => $this->vchr_Embed,
            'vchr_ChannelId' => $this->vchr_ChannelId,
            'vchr_tags' => $this->vchr_tags,
            'dt_Publicado' => $this->dt_Publicado
        ];
    }
}
