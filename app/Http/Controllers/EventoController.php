<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Eventos",
 *     description="API Endpoints para gerenciamento de eventos GEEK"
 * )
 */
class EventoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/events",
     *     summary="Listar todos os eventos",
     *     tags={"Eventos"},
     *     @OA\Parameter(
     *         name="cidade",
     *         in="query",
     *         description="Filtrar por cidade",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="data",
     *         in="query",
     *         description="Filtrar por data (formato: Y-m-d)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="tipo_atracao",
     *         in="query",
     *         description="Filtrar por tipo de atração ID",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de eventos retornada com sucesso",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Evento")
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $query = Evento::with(['agendas', 'atracoes.tipoAtracao', 'concursos.tipoConcurso', 'galerias']);

        // Filtro por cidade
        if ($request->has('cidade')) {
            $query->whereHas('agendas', function ($q) use ($request) {
                $q->where('cidade', 'like', '%' . $request->cidade . '%');
            });
        }

        // Filtro por data
        if ($request->has('data')) {
            $query->whereHas('agendas', function ($q) use ($request) {
                $q->whereDate('data', $request->data);
            });
        }

        // Filtro por tipo de atração
        if ($request->has('tipo_atracao')) {
            $query->whereHas('atracoes', function ($q) use ($request) {
                $q->where('tipo_atracao_id', $request->tipo_atracao);
            });
        }

        $eventos = $query->get();

        return response()->json($eventos, 200);
    }

    /**
     * @OA\Post(
     *     path="/api/events",
     *     summary="Criar um novo evento",
     *     tags={"Eventos"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/EventoRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Evento criado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Evento")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação"
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $evento = Evento::create($request->all());

        // Adicionar agendas se fornecidas
        if ($request->has('agendas')) {
            foreach ($request->agendas as $agenda) {
                $evento->agendas()->create($agenda);
            }
        }

        // Adicionar atrações se fornecidas
        if ($request->has('atracoes')) {
            $evento->atracoes()->attach($request->atracoes);
        }

        // Adicionar concursos se fornecidos
        if ($request->has('concursos')) {
            $evento->concursos()->attach($request->concursos);
        }

        $evento->load(['agendas', 'atracoes.tipoAtracao', 'concursos.tipoConcurso', 'galerias']);

        return response()->json($evento, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/events/{id}",
     *     summary="Exibir detalhes de um evento específico",
     *     tags={"Eventos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do evento",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes do evento",
     *         @OA\JsonContent(ref="#/components/schemas/Evento")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Evento não encontrado"
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        $evento = Evento::with(['agendas', 'atracoes.tipoAtracao', 'concursos.tipoConcurso', 'galerias'])
                        ->findOrFail($id);

        return response()->json($evento, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/events/{id}",
     *     summary="Atualizar um evento existente",
     *     tags={"Eventos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do evento",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/EventoRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Evento atualizado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Evento")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Evento não encontrado"
     *     )
     * )
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $evento = Evento::findOrFail($id);
        $evento->update($request->all());

        // Atualizar atrações se fornecidas
        if ($request->has('atracoes')) {
            $evento->atracoes()->sync($request->atracoes);
        }

        // Atualizar concursos se fornecidos
        if ($request->has('concursos')) {
            $evento->concursos()->sync($request->concursos);
        }

        $evento->load(['agendas', 'atracoes.tipoAtracao', 'concursos.tipoConcurso', 'galerias']);

        return response()->json($evento, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/events/{id}",
     *     summary="Remover um evento",
     *     tags={"Eventos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do evento",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Evento removido com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Evento não encontrado"
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $evento = Evento::findOrFail($id);
        $evento->delete();

        return response()->json(null, 204);
    }
}
