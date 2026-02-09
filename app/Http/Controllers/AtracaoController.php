<?php

namespace App\Http\Controllers;

use App\Models\Atracao;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Atrações",
 *     description="API Endpoints para gerenciamento de atrações"
 * )
 */
class AtracaoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/events/atracoes",
     *     summary="Listar todas as atrações",
     *     tags={"Atrações"},
     *     @OA\Parameter(
     *         name="tipo_atracao_id",
     *         in="query",
     *         description="Filtrar por tipo de atração",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de atrações retornada com sucesso",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Atracao")
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $query = Atracao::with('tipoAtracao');

        if ($request->has('tipo_atracao_id')) {
            $query->where('tipo_atracao_id', $request->tipo_atracao_id);
        }

        $atracoes = $query->get();

        return response()->json($atracoes, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/events/atracoes",
     *     summary="Criar uma nova atração",
     *     tags={"Atrações"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/AtracaoRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Atração criada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Atracao")
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $atracao = Atracao::create($request->all());
        $atracao->load('tipoAtracao');

        return response()->json($atracao, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/events/atracoes/{id}",
     *     summary="Exibir detalhes de uma atração específica",
     *     tags={"Atrações"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID da atração",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes da atração",
     *         @OA\JsonContent(ref="#/components/schemas/Atracao")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Atração não encontrada"
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        $atracao = Atracao::with('tipoAtracao')->findOrFail($id);

        return response()->json($atracao, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/events/atracoes/{id}",
     *     summary="Atualizar uma atração existente",
     *     tags={"Atrações"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID da atração",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/AtracaoRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Atração atualizada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Atracao")
     *     )
     * )
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $atracao = Atracao::findOrFail($id);
        $atracao->update($request->all());
        $atracao->load('tipoAtracao');

        return response()->json($atracao, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/events/atracoes/{id}",
     *     summary="Remover uma atração",
     *     tags={"Atrações"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID da atração",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Atração removida com sucesso"
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $atracao = Atracao::findOrFail($id);
        $atracao->delete();

        return response()->json(null, 204);
    }
}
