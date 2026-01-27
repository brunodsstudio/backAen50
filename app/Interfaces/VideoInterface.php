<?php

namespace App\Interfaces;

use App\DTOs\VideoDto;

interface VideoInterface
{
    public function getAll();
    public function getAllWithPaginate(int $perPage = 10, int $page = 1);
    public function getById(int $id);
    public function getByVideoId(string $videoId);
    public function getByMateria(int $materiaId);
    public function getByArea(int $areaId, int $perPage = 10);
    public function getVideosHome(int $limit = 20);
    public function create(VideoDto $videoDTO);
    public function update(int $id, VideoDto $videoDTO);
    public function delete(int $id);
}
