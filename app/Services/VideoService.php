<?php

namespace App\Services;

use App\Interfaces\VideoInterface;
use App\DTOs\VideoDto;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class VideoService
{
    protected VideoInterface $videoRepository;

    public function __construct(VideoInterface $videoRepository)
    {
        $this->videoRepository = $videoRepository;
    }

    public function getAll()
    {
        return $this->videoRepository->getAll();
    }

    public function getAllWithPaginate(int $perPage = 10, int $page = 1)
    {
        return $this->videoRepository->getAllWithPaginate($perPage, $page);
    }

    public function getById(int $id)
    {
        return $this->videoRepository->getById($id);
    }

    public function getByVideoId(string $videoId)
    {
        return $this->videoRepository->getByVideoId($videoId);
    }

    public function getByMateria(int $materiaId)
    {
        return $this->videoRepository->getByMateria($materiaId);
    }

    public function getByArea(int $areaId, int $perPage = 10)
    {
        return $this->videoRepository->getByArea($areaId, $perPage);
    }

    public function getVideosHome(int $limit = 20)
    {
        return $this->videoRepository->getVideosHome($limit);
    }

    public function create(VideoDto $videoDTO)
    {
        try {
            return $this->videoRepository->create($videoDTO);
        } catch (Exception $e) {
            Log::error('Erro no service ao criar vídeo: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update(int $id, VideoDto $videoDTO)
    {
        try {
            return $this->videoRepository->update($id, $videoDTO);
        } catch (ModelNotFoundException $e) {
            Log::error('Vídeo não encontrado no service: ' . $id);
            throw $e;
        } catch (Exception $e) {
            Log::error('Erro no service ao atualizar vídeo: ' . $e->getMessage());
            throw $e;
        }
    }

    public function delete(int $id)
    {
        try {
            return $this->videoRepository->delete($id);
        } catch (ModelNotFoundException $e) {
            Log::error('Vídeo não encontrado no service: ' . $id);
            throw $e;
        } catch (Exception $e) {
            Log::error('Erro no service ao deletar vídeo: ' . $e->getMessage());
            throw $e;
        }
    }
}
