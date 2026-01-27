<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use App\Services\VideoService;
use App\Repositories\VideoRepository;
use App\DTOs\VideoDto;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Support\Facades\Log;

class VideoController extends Controller
{
    protected VideoService $videoService;

    public function __construct()
    {
        $this->videoService = new VideoService(new VideoRepository());
    }

    /**
     * @OA\Get(
     *     path="/videos",
     *     tags={"Vídeos"},
     *     summary="Listar todos os vídeos",
     *     description="Retorna uma lista paginada de todos os vídeos disponíveis",
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Número da página",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         description="Itens por página",
     *         required=false,
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de vídeos retornada com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Video")),
     *             @OA\Property(property="current_page", type="integer"),
     *             @OA\Property(property="per_page", type="integer"),
     *             @OA\Property(property="total", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor",
     *         @OA\JsonContent(@OA\Property(property="error", type="string"))
     *     )
     * )
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->query('perPage', 10);
            $page = $request->query('page', 1);
            
            $videos = $this->videoService->getAllWithPaginate($perPage, $page);
            
            return response()->json($videos, 200);
        } catch (Exception $e) {
            Log::error('Error retrieving videos: ' . $e->getMessage());
            return response()->json(['error' => 'Could not retrieve videos.'], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/videos",
     *     tags={"Vídeos"},
     *     summary="Criar um novo vídeo",
     *     description="Cria um novo vídeo no sistema",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"vchr_Titulo", "vchr_Description", "vchr_YTThumbDefault", "vchr_YTThumbMedium", "vchr_YTThumbHigh", "vchr_Embed", "vchr_ChannelId", "vchr_tags"},
     *             @OA\Property(property="int_IdMateria", type="integer", description="ID da matéria associada"),
     *             @OA\Property(property="vchr_VideoId", type="string", maxLength=50, description="ID do vídeo no YouTube"),
     *             @OA\Property(property="vchr_LinkVideo", type="string", maxLength=300, description="Link do vídeo"),
     *             @OA\Property(property="int_IdArea", type="integer", description="ID da área"),
     *             @OA\Property(property="vchr_Titulo", type="string", maxLength=400, description="Título do vídeo"),
     *             @OA\Property(property="vchr_Description", type="string", description="Descrição do vídeo"),
     *             @OA\Property(property="vchr_YTThumbDefault", type="string", maxLength=300, description="Thumbnail padrão do YouTube"),
     *             @OA\Property(property="vchr_YTThumbMedium", type="string", maxLength=300, description="Thumbnail média do YouTube"),
     *             @OA\Property(property="vchr_YTThumbHigh", type="string", maxLength=300, description="Thumbnail alta do YouTube"),
     *             @OA\Property(property="vchr_Embed", type="string", description="Código embed do vídeo"),
     *             @OA\Property(property="vchr_ChannelId", type="string", maxLength=50, description="ID do canal do YouTube"),
     *             @OA\Property(property="vchr_tags", type="string", maxLength=400, description="Tags do vídeo"),
     *             @OA\Property(property="dt_Publicado", type="string", format="date-time", description="Data de publicação no YouTube")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Vídeo criado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Video")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Dados de entrada inválidos",
     *         @OA\JsonContent(@OA\Property(property="errors", type="object"))
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor",
     *         @OA\JsonContent(@OA\Property(property="error", type="string"))
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vchr_Titulo' => 'required|string|max:400',
            'vchr_Description' => 'required|string',
            'vchr_YTThumbDefault' => 'required|string|max:300',
            'vchr_YTThumbMedium' => 'required|string|max:300',
            'vchr_YTThumbHigh' => 'required|string|max:300',
            'vchr_Embed' => 'required|string',
            'vchr_ChannelId' => 'required|string|max:50',
            'vchr_tags' => 'required|string|max:400',
            'int_IdMateria' => 'nullable|integer',
            'vchr_VideoId' => 'nullable|string|max:50',
            'vchr_LinkVideo' => 'nullable|string|max:300',
            'int_IdArea' => 'nullable|integer',
            'dt_Publicado' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $videoDTO = VideoDto::fromArray(array_merge(['id' => 0], $request->all()));
            $video = $this->videoService->create($videoDTO);
            
            return response()->json($video, 201);
        } catch (Exception $e) {
            Log::error('Error creating video: ' . $e->getMessage());
            return response()->json(['error' => 'Could not create video.'], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/videos/{id}",
     *     tags={"Vídeos"},
     *     summary="Buscar vídeo por ID",
     *     description="Retorna um vídeo específico pelo seu ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do vídeo",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vídeo encontrado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Video")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Vídeo não encontrado",
     *         @OA\JsonContent(@OA\Property(property="error", type="string"))
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor",
     *         @OA\JsonContent(@OA\Property(property="error", type="string"))
     *     )
     * )
     */
    public function show(int $id)
    {
        try {
            $video = $this->videoService->getById($id);
            
            if (!$video) {
                return response()->json(['error' => 'Video not found.'], 404);
            }
            
            return response()->json($video, 200);
        } catch (Exception $e) {
            Log::error('Error retrieving video: ' . $e->getMessage());
            return response()->json(['error' => 'Could not retrieve video.'], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/videos/{id}",
     *     tags={"Vídeos"},
     *     summary="Atualizar um vídeo",
     *     description="Atualiza os dados de um vídeo existente",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do vídeo",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="int_IdMateria", type="integer", description="ID da matéria associada"),
     *             @OA\Property(property="vchr_VideoId", type="string", maxLength=50, description="ID do vídeo no YouTube"),
     *             @OA\Property(property="vchr_LinkVideo", type="string", maxLength=300, description="Link do vídeo"),
     *             @OA\Property(property="int_IdArea", type="integer", description="ID da área"),
     *             @OA\Property(property="vchr_Titulo", type="string", maxLength=400, description="Título do vídeo"),
     *             @OA\Property(property="vchr_Description", type="string", description="Descrição do vídeo"),
     *             @OA\Property(property="vchr_YTThumbDefault", type="string", maxLength=300, description="Thumbnail padrão"),
     *             @OA\Property(property="vchr_YTThumbMedium", type="string", maxLength=300, description="Thumbnail média"),
     *             @OA\Property(property="vchr_YTThumbHigh", type="string", maxLength=300, description="Thumbnail alta"),
     *             @OA\Property(property="vchr_Embed", type="string", description="Código embed"),
     *             @OA\Property(property="vchr_ChannelId", type="string", maxLength=50, description="ID do canal"),
     *             @OA\Property(property="vchr_tags", type="string", maxLength=400, description="Tags"),
     *             @OA\Property(property="dt_Publicado", type="string", format="date-time", description="Data de publicação")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vídeo atualizado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Video")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Vídeo não encontrado",
     *         @OA\JsonContent(@OA\Property(property="error", type="string"))
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Dados de entrada inválidos",
     *         @OA\JsonContent(@OA\Property(property="errors", type="object"))
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor",
     *         @OA\JsonContent(@OA\Property(property="error", type="string"))
     *     )
     * )
     */
    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'vchr_Titulo' => 'sometimes|required|string|max:400',
            'vchr_Description' => 'sometimes|required|string',
            'vchr_YTThumbDefault' => 'sometimes|required|string|max:300',
            'vchr_YTThumbMedium' => 'sometimes|required|string|max:300',
            'vchr_YTThumbHigh' => 'sometimes|required|string|max:300',
            'vchr_Embed' => 'sometimes|required|string',
            'vchr_ChannelId' => 'sometimes|required|string|max:50',
            'vchr_tags' => 'sometimes|required|string|max:400',
            'int_IdMateria' => 'nullable|integer',
            'vchr_VideoId' => 'nullable|string|max:50',
            'vchr_LinkVideo' => 'nullable|string|max:300',
            'int_IdArea' => 'nullable|integer',
            'dt_Publicado' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $videoDTO = VideoDto::fromArray(array_merge(['id' => $id], $request->all()));
            $video = $this->videoService->update($id, $videoDTO);
            
            return response()->json($video, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Video not found.'], 404);
        } catch (Exception $e) {
            Log::error('Error updating video: ' . $e->getMessage());
            return response()->json(['error' => 'Could not update video.'], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/videos/{id}",
     *     tags={"Vídeos"},
     *     summary="Deletar um vídeo",
     *     description="Remove um vídeo do sistema",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do vídeo",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Vídeo deletado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Vídeo não encontrado",
     *         @OA\JsonContent(@OA\Property(property="error", type="string"))
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor",
     *         @OA\JsonContent(@OA\Property(property="error", type="string"))
     *     )
     * )
     */
    public function destroy(int $id)
    {
        try {
            $this->videoService->delete($id);
            
            return response()->json(null, 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Video not found.'], 404);
        } catch (Exception $e) {
            Log::error('Error deleting video: ' . $e->getMessage());
            return response()->json(['error' => 'Could not delete video.'], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/videos/youtube/{videoId}",
     *     tags={"Vídeos"},
     *     summary="Buscar vídeo por ID do YouTube",
     *     description="Retorna um vídeo específico pelo seu ID do YouTube",
     *     @OA\Parameter(
     *         name="videoId",
     *         in="path",
     *         description="ID do vídeo no YouTube",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vídeo encontrado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Video")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Vídeo não encontrado",
     *         @OA\JsonContent(@OA\Property(property="error", type="string"))
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor",
     *         @OA\JsonContent(@OA\Property(property="error", type="string"))
     *     )
     * )
     */
    public function getByVideoId(string $videoId)
    {
        try {
            $video = $this->videoService->getByVideoId($videoId);
            
            if (!$video) {
                return response()->json(['error' => 'Video not found.'], 404);
            }
            
            return response()->json($video, 200);
        } catch (Exception $e) {
            Log::error('Error retrieving video by YouTube ID: ' . $e->getMessage());
            return response()->json(['error' => 'Could not retrieve video.'], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/videos/materia/{materiaId}",
     *     tags={"Vídeos"},
     *     summary="Listar vídeos por matéria",
     *     description="Retorna todos os vídeos associados a uma matéria específica",
     *     @OA\Parameter(
     *         name="materiaId",
     *         in="path",
     *         description="ID da matéria",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de vídeos retornada com sucesso",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Video"))
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor",
     *         @OA\JsonContent(@OA\Property(property="error", type="string"))
     *     )
     * )
     */
    public function getByMateria(int $materiaId)
    {
        try {
            $videos = $this->videoService->getByMateria($materiaId);
            
            return response()->json($videos, 200);
        } catch (Exception $e) {
            Log::error('Error retrieving videos by materia: ' . $e->getMessage());
            return response()->json(['error' => 'Could not retrieve videos.'], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/videos/area/{areaId}",
     *     tags={"Vídeos"},
     *     summary="Listar vídeos por área",
     *     description="Retorna todos os vídeos de uma área específica com paginação",
     *     @OA\Parameter(
     *         name="areaId",
     *         in="path",
     *         description="ID da área",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         description="Itens por página",
     *         required=false,
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de vídeos retornada com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Video")),
     *             @OA\Property(property="current_page", type="integer"),
     *             @OA\Property(property="per_page", type="integer"),
     *             @OA\Property(property="total", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor",
     *         @OA\JsonContent(@OA\Property(property="error", type="string"))
     *     )
     * )
     */
    public function getByArea(Request $request, int $areaId)
    {
        try {
            $perPage = $request->query('perPage', 10);
            $videos = $this->videoService->getByArea($areaId, $perPage);
            
            return response()->json($videos, 200);
        } catch (Exception $e) {
            Log::error('Error retrieving videos by area: ' . $e->getMessage());
            return response()->json(['error' => 'Could not retrieve videos.'], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/videos/home",
     *     tags={"Vídeos"},
     *     summary="Listar vídeos para a home",
     *     description="Retorna os vídeos mais recentes para exibição na página inicial",
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Número máximo de vídeos",
     *         required=false,
     *         @OA\Schema(type="integer", default=20)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de vídeos retornada com sucesso",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Video"))
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor",
     *         @OA\JsonContent(@OA\Property(property="error", type="string"))
     *     )
     * )
     */
    public function getVideosHome(Request $request)
    {
        try {
            $limit = $request->query('limit', 20);
            $videos = $this->videoService->getVideosHome($limit);
            
            return response()->json($videos, 200);
        } catch (Exception $e) {
            Log::error('Error retrieving home videos: ' . $e->getMessage());
            return response()->json(['error' => 'Could not retrieve videos.'], 500);
        }
    }
}
