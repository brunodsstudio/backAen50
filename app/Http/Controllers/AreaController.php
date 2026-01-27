<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Http\Resources\AreaResource;
use Illuminate\Http\Request;
use App\Services\AreaService;
use App\DTOs\AreaDto;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AreaController extends Controller
{
    protected AreaService $areaService;

    public function __construct(AreaService $areaService)
    {
        $this->areaService = $areaService;
    }

    /**
     * @OA\Get(
     *     path="/api/areas",
     *     tags={"Areas"},
     *     summary="Listar todas as áreas",
     *     description="Retorna uma lista paginada de todas as áreas disponíveis ordenadas alfabeticamente",
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
     *         description="Lista de áreas retornada com sucesso",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Area")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Could not retrieve areas.")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->query('perPage', 10);
            $page = $request->query('page', 1);
            
            $areas = $this->areaService->getAllWithPaginate($perPage, $page);
            
            return response()->json(AreaResource::collection($areas->items()), 200);
        } catch (Exception $e) {
            Log::error('Error retrieving areas: ' . $e->getMessage());
            return response()->json(['error' => 'Could not retrieve areas.'], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/areas/{id}",
     *     tags={"Areas"},
     *     summary="Obter uma área específica",
     *     description="Retorna os detalhes de uma área específica pelo ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da área",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Área encontrada",
     *         @OA\JsonContent(ref="#/components/schemas/Area")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Área não encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Area not found.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Could not retrieve area.")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $area = $this->areaService->getById($id);
            return response()->json(new AreaResource($area), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Area not found.'], 404);
        } catch (Exception $e) {
            Log::error('Error retrieving area: ' . $e->getMessage());
            return response()->json(['error' => 'Could not retrieve area.'], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/areas",
     *     tags={"Areas"},
     *     summary="Criar uma nova área",
     *     description="Cria uma nova área no sistema",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"vchr_AreaNome"},
     *             @OA\Property(property="vchr_AreaNome", type="string", maxLength=255, description="Nome da área", example="Quadrinhos"),
     *             @OA\Property(property="vchr_Tag", type="string", maxLength=255, description="Tag da área", example="comics"),
     *             @OA\Property(property="type", type="string", enum={"bd", "pasta"}, description="Tipo da área", example="bd"),
     *             @OA\Property(property="b_menu", type="boolean", description="Se aparece no menu", example=true),
     *             @OA\Property(property="int_rolePermission", type="integer", description="Permissão de role", example=0)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Área criada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Area")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Dados de entrada inválidos",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Could not create area.")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vchr_AreaNome' => 'required|string|max:255',
            'vchr_Tag' => 'nullable|string|max:255',
            'type' => 'nullable|string|in:bd,pasta',
            'b_menu' => 'nullable|boolean',
            'int_rolePermission' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $areaDTO = new AreaDto(
                int_Id: 0,
                vchr_AreaNome: $request->input('vchr_AreaNome'),
                vchr_Tag: $request->input('vchr_Tag'),
                type: $request->input('type'),
                b_menu: $request->input('b_menu', false),
                int_rolePermission: $request->input('int_rolePermission', 0)
            );

            $area = $this->areaService->create($areaDTO);
            return response()->json(new AreaResource($area), 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (Exception $e) {
            Log::error('Error creating area: ' . $e->getMessage());
            return response()->json(['error' => 'Could not create area.'], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/areas/{id}",
     *     tags={"Areas"},
     *     summary="Atualizar uma área existente",
     *     description="Atualiza os dados de uma área específica",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da área",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"vchr_AreaNome"},
     *             @OA\Property(property="vchr_AreaNome", type="string", maxLength=255, description="Nome da área", example="Quadrinhos"),
     *             @OA\Property(property="vchr_Tag", type="string", maxLength=255, description="Tag da área", example="comics"),
     *             @OA\Property(property="type", type="string", enum={"bd", "pasta"}, description="Tipo da área", example="bd"),
     *             @OA\Property(property="b_menu", type="boolean", description="Se aparece no menu", example=true),
     *             @OA\Property(property="int_rolePermission", type="integer", description="Permissão de role", example=0)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Área atualizada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Area")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Área não encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Area not found.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Dados de entrada inválidos",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Could not update area.")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'vchr_AreaNome' => 'required|string|max:255',
            'vchr_Tag' => 'nullable|string|max:255',
            'type' => 'nullable|string|in:bd,pasta',
            'b_menu' => 'nullable|boolean',
            'int_rolePermission' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $areaDTO = new AreaDto(
                int_Id: $id,
                vchr_AreaNome: $request->input('vchr_AreaNome'),
                vchr_Tag: $request->input('vchr_Tag'),
                type: $request->input('type'),
                b_menu: $request->input('b_menu', false),
                int_rolePermission: $request->input('int_rolePermission', 0)
            );

            $area = $this->areaService->update($id, $areaDTO);
            return response()->json(new AreaResource($area), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Area not found.'], 404);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (Exception $e) {
            Log::error('Error updating area: ' . $e->getMessage());
            return response()->json(['error' => 'Could not update area.'], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/areas/{id}",
     *     tags={"Areas"},
     *     summary="Excluir uma área",
     *     description="Remove uma área do sistema",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da área",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Área excluída com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Área não encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Area not found.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Could not delete area.")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            $this->areaService->delete($id);
            return response()->json(null, 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Area not found.'], 404);
        } catch (Exception $e) {
            Log::error('Error deleting area: ' . $e->getMessage());
            return response()->json(['error' => 'Could not delete area.'], 500);
        }
    } 
}  
