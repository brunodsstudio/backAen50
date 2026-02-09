<?php

namespace App\Repositories;

use App\Interfaces\VideoInterface;
use App\DTOs\VideoDto;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use App\Models\Video;

class VideoRepository implements VideoInterface
{
    public function getAll()
    {
        return Video::with(['materia:id,vchr_titulo', 'area:int_Id,vchr_AreaNome'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getAllWithPaginate(int $perPage = 10, int $page = 1)
    {
        return Video::select(
            'id',
            'int_IdMateria',
            'vchr_VideoId',
            'vchr_LinkVideo',
            'int_IdArea',
            'vchr_Titulo',
            'vchr_YTThumbDefault',
            'vchr_YTThumbMedium',
            'vchr_YTThumbHigh',
            'vchr_ChannelId',
            'vchr_tags',
            'dt_Publicado',
            'created_at'
        )
        ->with(['materia:id,vchr_titulo', 'area:int_Id,vchr_AreaNome'])
        ->orderBy('dt_Publicado', 'desc')
        ->paginate($perPage, ['*'], 'page', $page);
    }

    public function getById(int $id)
    {
        return Video::with(['materia', 'area'])->find($id);
    }

    public function getByVideoId(string $videoId)
    {
        return Video::with(['materia', 'area'])
            ->where('vchr_VideoId', $videoId)
            ->first();
    }

    public function getByMateria(int $materiaId)
    {
        return Video::with(['area:int_Id,vchr_AreaNome'])
            ->where('int_IdMateria', $materiaId)
            ->orderBy('dt_Publicado', 'desc')
            ->get();
    }

    public function getByArea(int $areaId, int $perPage = 10)
    {
        return Video::select(
            'id',
            'int_IdMateria',
            'vchr_VideoId',
            'vchr_LinkVideo',
            'int_IdArea',
            'vchr_Titulo',
            'vchr_Description',
            'vchr_YTThumbDefault',
            'vchr_YTThumbMedium',
            'vchr_YTThumbHigh',
            'vchr_ChannelId',
            'vchr_tags',
            'dt_Publicado'
        )
        ->with(['materia:id,vchr_titulo'])
        ->where('int_IdArea', $areaId)
        ->orderBy('dt_Publicado', 'desc')
        ->paginate($perPage);
    }

    public function getVideosHome(int $limit = 20)
    {
        return Video::select(
            'id',
            'int_IdMateria',
            'vchr_VideoId',
            'vchr_LinkVideo',
            'int_IdArea',
            'vchr_Titulo',
            'vchr_Description',
            'vchr_YTThumbDefault',
            'vchr_YTThumbMedium',
            'vchr_YTThumbHigh',
            'vchr_Embed',
            'vchr_ChannelId',
            'vchr_tags',
            'dt_Publicado'
        )
        ->with(['materia:id,vchr_titulo', 'area:int_Id,vchr_AreaNome'])
        ->orderBy('dt_Publicado', 'desc')
        ->limit($limit)
        ->get();
    }

    public function create(VideoDto $videoDTO)
    {
        try {
            $data = $videoDTO->toArray();
            unset($data['id']); // Remove id do array para criação
            return Video::create($data);
        } catch (Exception $e) {
            Log::error('Erro ao criar vídeo: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update(int $id, VideoDto $videoDTO)
    {
        try {
            $video = Video::findOrFail($id);
            $data = $videoDTO->toArray();
            unset($data['id']); // Remove id do array para atualização
            $video->update($data);
            return $video;
        } catch (ModelNotFoundException $e) {
            Log::error('Vídeo não encontrado: ' . $id);
            throw $e;
        } catch (Exception $e) {
            Log::error('Erro ao atualizar vídeo: ' . $e->getMessage());
            throw $e;
        }
    }

    public function delete(int $id)
    {
        try {
            $video = Video::findOrFail($id);
            return $video->delete();
        } catch (ModelNotFoundException $e) {
            Log::error('Vídeo não encontrado: ' . $id);
            throw $e;
        } catch (Exception $e) {
            Log::error('Erro ao deletar vídeo: ' . $e->getMessage());
            throw $e;
        }
    }
}
