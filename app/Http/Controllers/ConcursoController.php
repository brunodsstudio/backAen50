<?php

namespace App\Http\Controllers;

use App\Models\Concurso;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Concursos",
 *     description="API Endpoints para gerenciamento de concursos"
 * )
 */
class ConcursoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/events/concursos",
     *     summary="Listar todos os concursos",
     *     tags={"Concursos"},
     *     @OA\Parameter(
     *         name="tipo_concurso_id",
     *         in="query",
     *         description="Filtrar por tipo de concurso",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de concursos retornada com sucesso",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Concurso")
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $query = Concurso::with('tipoConcurso');

        if ($request->has('tipo_concurso_id')) {
            $query->where('tipo_concurso_id', $request->tipo_concurso_id);
        }

        $concursos = $query->get();

        return response()->json($concursos, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/events/concursos",
     *     summary="Criar um novo concurso",
     *     tags={"Concursos"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ConcursoRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Concurso criado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Concurso")
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $concurso = Concurso::create($request->all());
        $concurso->load('tipoConcurso');

        return response()->json($concurso, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/events/concursos/{id}",
     *     summary="Exibir detalhes de um concurso específico",
     *     tags={"Concursos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do concurso",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes do concurso",
     *         @OA\JsonContent(ref="#/components/schemas/Concurso")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Concurso não encontrado"
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        $concurso = Concurso::with('tipoConcurso')->findOrFail($id);

        return response()->json($concurso, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/events/concursos/{id}",
     *     summary="Atualizar um concurso existente",
     *     tags={"Concursos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do concurso",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ConcursoRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Concurso atualizado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Concurso")
     *     )
     * )
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $concurso = Concurso::findOrFail($id);
        $concurso->update($request->all());
        $concurso->load('tipoConcurso');

        return response()->json($concurso, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/events/concursos/{id}",
     *     summary="Remover um concurso",
     *     tags={"Concursos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do concurso",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Concurso removido com sucesso"
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $concurso = Concurso::findOrFail($id);
        $concurso->delete();

        return response()->json(null, 204);
    }
}
