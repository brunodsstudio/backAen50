<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use App\Http\Resources\MateriaResource;
use Illuminate\Http\Request;
use App\Services\MateriaService;
use App\Repositories\MateriaRepository;
use App\DTOs\MateriaDto;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Support\Facades\Log;

class MateriaController extends Controller
{
    protected MateriaService $materiaService;

    public function __construct()
    {
        $this->materiaService = new MateriaService(new MateriaRepository());
    }

    /**
     * @OA\Get(
     *     path="/materias",
     *     tags={"Matérias"},
     *     summary="Listar todas as matérias",
     *     description="Retorna uma lista paginada de todas as matérias disponíveis",
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
     *         description="Lista de matérias retornada com sucesso",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Materia")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->query('perPage', 10);
            $page = $request->query('page', 1);
            
            $materia = $this->materiaService->getAllWithPaginate($perPage, $page);
            
            return response()->json(MateriaResource::collection($materia->items()), 200);
        } catch (Exception $e) {
            Log::error('Error retrieving materias: ' . $e->getMessage());
            return response()->json(['error' => 'Could not retrieve materias.'], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * @OA\Post(
     *     path="/materias",
     *     tags={"Matérias"},
     *     summary="Criar uma nova matéria",
     *     description="Cria uma nova matéria no sistema",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"vchr_titulo", "vchr_conteudo"},
     *             @OA\Property(property="dt_post", type="string", format="date-time", description="Data de publicação"),
     *             @OA\Property(property="vchr_autor", type="string", description="Nome do autor"),
     *             @OA\Property(property="int_autor", type="integer", description="ID do autor"),
     *             @OA\Property(property="vchr_lide", type="string", description="Lide da matéria"),
     *             @OA\Property(property="vchr_titulo", type="string", description="Título da matéria"),
     *             @OA\Property(property="vchr_conteudo", type="string", description="Conteúdo da matéria"),
     *             @OA\Property(property="vchr_area", type="string", description="Nome da área"),
     *             @OA\Property(property="id_area", type="integer", description="ID da área"),
     *             @OA\Property(property="vchr_tags", type="string", description="Tags da matéria"),
     *             @OA\Property(property="vchr_FontLink", type="string", description="Link da fonte"),
     *             @OA\Property(property="vchr_LinkTitulo", type="string", description="Título do link"),
     *             @OA\Property(property="vchr_seoTitle", type="string", description="Título SEO"),
     *             @OA\Property(property="vchr_seoKeywords", type="string", description="Palavras-chave SEO"),
     *             @OA\Property(property="og_title", type="string", description="Título Open Graph"),
     *             @OA\Property(property="og_description", type="string", description="Descrição Open Graph"),
     *             @OA\Property(property="og_image", type="string", description="Imagem Open Graph"),
     *             @OA\Property(property="vchr_s3_storage", type="string", description="Armazenamento S3"),
     *             @OA\Property(property="bool_onLine", type="boolean", description="Se está online"),
     *             @OA\Property(property="bool_home", type="boolean", description="Se aparece na home"),
     *             @OA\Property(property="base64Format", type="boolean", description="Formato base64"),
     *             @OA\Property(property="materiaUUID", type="string", description="UUID da matéria"),
     *             @OA\Property(property="IdSocialIconTemplate", type="integer", description="ID do template de ícone social"),
     *             @OA\Property(property="vchr_GalDir", type="string", description="Diretório da galeria")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Matéria criada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Materia")
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
     *             @OA\Property(property="error", type="string", example="Could not create materia.")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vchr_titulo' => 'required|string|max:255',
            'vchr_conteudo' => 'required|string',
            'dt_post' => 'nullable|date',
            'vchr_autor' => 'nullable|string|max:255',
            'int_autor' => 'nullable|integer',
            'vchr_lide' => 'nullable|string',
            'vchr_area' => 'nullable|string|max:255',
            'id_area' => 'nullable|integer',
            'vchr_tags' => 'nullable|string',
            'vchr_FontLink' => 'nullable|string|max:255',
            'vchr_LinkTitulo' => 'nullable|string|max:255',
            'vchr_seoTitle' => 'nullable|string|max:255',
            'vchr_seoKeywords' => 'nullable|string',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string',
            'og_image' => 'nullable|string|max:255',
            'vchr_s3_storage' => 'nullable|string|max:255',
            'bool_onLine' => 'nullable|boolean',
            'bool_home' => 'nullable|boolean',
            'base64Format' => 'nullable|boolean',
            'materiaUUID' => 'nullable|string|max:255',
            'IdSocialIconTemplate' => 'nullable|integer',
            'vchr_GalDir' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $materiaDTO = new MateriaDto(
                id: 0,
                dt_post: $request->input('dt_post'),
                vchr_autor: $request->input('vchr_autor'),
                int_autor: $request->input('int_autor'),
                vchr_lide: $request->input('vchr_lide'),
                vchr_titulo: $request->input('vchr_titulo'),
                vchr_conteudo: $request->input('vchr_conteudo'),
                vchr_area: $request->input('vchr_area'),
                id_area: $request->input('id_area'),
                vchr_tags: $request->input('vchr_tags'),
                vchr_FontLink: $request->input('vchr_FontLink'),
                vchr_LinkTitulo: $request->input('vchr_LinkTitulo'),
                vchr_seoTitle: $request->input('vchr_seoTitle'),
                vchr_seoKeywords: $request->input('vchr_seoKeywords'),
                og_title: $request->input('og_title'),
                og_description: $request->input('og_description'),
                og_image: $request->input('og_image'),
                vchr_s3_storage: $request->input('vchr_s3_storage'),
                bool_onLine: $request->input('bool_onLine', false),
                bool_home: $request->input('bool_home', false),
                base64Format: $request->input('base64Format', false),
                materiaUUID: $request->input('materiaUUID'),
                IdSocialIconTemplate: $request->input('IdSocialIconTemplate'),
                dt_alterado: null,
                vchr_GalDir: $request->input('vchr_GalDir')
            );

            $materia = $this->materiaService->create($materiaDTO);
            return response()->json(new MateriaResource($materia), 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (Exception $e) {
            Log::error('Error creating materia: ' . $e->getMessage());
            return response()->json(['error' => 'Could not create materia.'], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/materias/{id}",
     *     tags={"Matérias"},
     *     summary="Obter uma matéria específica",
     *     description="Retorna os detalhes de uma matéria específica pelo ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da matéria",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Matéria encontrada",
     *         @OA\JsonContent(ref="#/components/schemas/Materia")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Matéria não encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Materia not found.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Could not retrieve materia.")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $materia = $this->materiaService->getById($id);
            return response()->json(new MateriaResource($materia), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Materia not found.'], 404);
        } catch (Exception $e) {
            Log::error('Error retrieving materia: ' . $e->getMessage());
            return response()->json(['error' => 'Could not retrieve materia.'], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(materia $materia)
    {
        //
    }

    /**
     * @OA\Put(
     *     path="/materias/{id}",
     *     tags={"Matérias"},
     *     summary="Atualizar uma matéria",
     *     description="Atualiza os dados de uma matéria existente",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da matéria",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="dt_post", type="string", format="date-time", description="Data de publicação"),
     *             @OA\Property(property="vchr_autor", type="string", description="Nome do autor"),
     *             @OA\Property(property="int_autor", type="integer", description="ID do autor"),
     *             @OA\Property(property="vchr_lide", type="string", description="Lide da matéria"),
     *             @OA\Property(property="vchr_titulo", type="string", description="Título da matéria"),
     *             @OA\Property(property="vchr_conteudo", type="string", description="Conteúdo da matéria"),
     *             @OA\Property(property="vchr_area", type="string", description="Nome da área"),
     *             @OA\Property(property="id_area", type="integer", description="ID da área"),
     *             @OA\Property(property="vchr_tags", type="string", description="Tags da matéria"),
     *             @OA\Property(property="bool_onLine", type="boolean", description="Se está online"),
     *             @OA\Property(property="bool_home", type="boolean", description="Se aparece na home")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Matéria atualizada com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Materia")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Matéria não encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Materia not found.")
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
     *             @OA\Property(property="error", type="string", example="Could not update materia.")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'vchr_titulo' => 'nullable|string|max:255',
            'vchr_conteudo' => 'nullable|string',
            'dt_post' => 'nullable|date',
            'vchr_autor' => 'nullable|string|max:255',
            'int_autor' => 'nullable|integer',
            'vchr_lide' => 'nullable|string',
            'vchr_area' => 'nullable|string|max:255',
            'id_area' => 'nullable|integer',
            'vchr_tags' => 'nullable|string',
            'bool_onLine' => 'nullable|boolean',
            'bool_home' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            // Busca a matéria existente para manter os dados não alterados
            $existingMateria = $this->materiaService->getById($id);
            
            $materiaDTO = new MateriaDto(
                id: $id,
                dt_post: $request->input('dt_post', $existingMateria->dt_post),
                vchr_autor: $request->input('vchr_autor', $existingMateria->vchr_autor),
                int_autor: $request->input('int_autor', $existingMateria->int_autor),
                vchr_lide: $request->input('vchr_lide', $existingMateria->vchr_lide),
                vchr_titulo: $request->input('vchr_titulo', $existingMateria->vchr_titulo),
                vchr_conteudo: $request->input('vchr_conteudo', $existingMateria->vchr_conteudo),
                vchr_area: $request->input('vchr_area', $existingMateria->vchr_area),
                id_area: $request->input('id_area', $existingMateria->id_area),
                vchr_tags: $request->input('vchr_tags', $existingMateria->vchr_tags),
                vchr_FontLink: $request->input('vchr_FontLink', $existingMateria->vchr_FontLink),
                vchr_LinkTitulo: $request->input('vchr_LinkTitulo', $existingMateria->vchr_LinkTitulo),
                vchr_seoTitle: $request->input('vchr_seoTitle', $existingMateria->vchr_seoTitle),
                vchr_seoKeywords: $request->input('vchr_seoKeywords', $existingMateria->vchr_seoKeywords),
                og_title: $request->input('og_title', $existingMateria->og_title),
                og_description: $request->input('og_description', $existingMateria->og_description),
                og_image: $request->input('og_image', $existingMateria->og_image),
                vchr_s3_storage: $request->input('vchr_s3_storage', $existingMateria->vchr_s3_storage),
                bool_onLine: $request->input('bool_onLine', $existingMateria->bool_onLine),
                bool_home: $request->input('bool_home', $existingMateria->bool_home),
                base64Format: $request->input('base64Format', $existingMateria->base64Format),
                materiaUUID: $request->input('materiaUUID', $existingMateria->materiaUUID),
                IdSocialIconTemplate: $request->input('IdSocialIconTemplate', $existingMateria->IdSocialIconTemplate),
                dt_alterado: now()->toDateTimeString(),
                vchr_GalDir: $request->input('vchr_GalDir', $existingMateria->vchr_GalDir)
            );

            $materia = $this->materiaService->update($id, $materiaDTO);
            return response()->json(new MateriaResource($materia), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Materia not found.'], 404);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (Exception $e) {
            Log::error('Error updating materia: ' . $e->getMessage());
            return response()->json(['error' => 'Could not update materia.'], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/materias/{id}",
     *     tags={"Matérias"},
     *     summary="Deletar uma matéria",
     *     description="Remove uma matéria do sistema",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da matéria",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Matéria deletada com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Matéria não encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Materia not found.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Could not delete materia.")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            $this->materiaService->delete($id);
            return response()->json(null, 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Materia not found.'], 404);
        } catch (Exception $e) {
            Log::error('Error deleting materia: ' . $e->getMessage());
            return response()->json(['error' => 'Could not delete materia.'], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/MateriasHome",
     *     tags={"Matérias"},
     *     summary="Listar matérias para a home",
     *     description="Retorna matérias ativas (bool_home=1 e bool_onLine=1) ordenadas por data de criação descendente, com suas respectivas imagens. Campos retornados das imagens: vchr_Tipo e vchr_HRef",
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Limite de registros a retornar (padrão: 20)",
     *         required=false,
     *         @OA\Schema(type="integer", default=20, example=20)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de matérias da home retornada com sucesso",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="vchr_titulo", type="string", example="Título da Matéria"),
     *                 @OA\Property(property="vchr_lide", type="string", example="Lide da matéria"),
     *                 @OA\Property(property="vchr_area", type="string", example="Tecnologia"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2026-01-16T10:30:00Z"),
     *                 @OA\Property(
     *                     property="images",
     *                     type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="int_Id", type="integer", example=1),
     *                         @OA\Property(property="vchr_Tipo", type="string", example="Slider_Home"),
     *                         @OA\Property(property="vchr_HRef", type="string", example="https://example.com/image.jpg")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Could not retrieve materias home.")
     *         )
     *     )
     * )
     */
    public function materiasHome(Request $request)
    {
        try {
            $limit = $request->query('limit', 20);
            $materias = $this->materiaService->getMateriasHome($limit);
            
            return response()->json($materias, 200);
        } catch (Exception $e) {
            Log::error('Error retrieving materias home: ' . $e->getMessage());
            return response()->json(['error' => 'Could not retrieve materias home.'], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/MateriasCategoria",
     *     tags={"Matérias"},
     *     summary="Listar matérias por categoria com paginação",
     *     description="Retorna matérias ativas (bool_onLine=1) filtradas por categoria (id_area), com paginação e imagens do tipo 'Top_Materia'. Ordenação padrão por data de criação descendente.",
     *     @OA\Parameter(
     *         name="id_area",
     *         in="query",
     *         description="ID da área/categoria (obrigatório)",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Número da página (padrão: 1)",
     *         required=false,
     *         @OA\Schema(type="integer", default=1, example=1)
     *     ),
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         description="Itens por página (padrão: 10)",
     *         required=false,
     *         @OA\Schema(type="integer", default=10, example=10)
     *     ),
     *     @OA\Parameter(
     *         name="orderBy",
     *         in="query",
     *         description="Campo para ordenação (opções: created_at, dt_post, vchr_titulo, vchr_autor. Padrão: created_at)",
     *         required=false,
     *         @OA\Schema(type="string", default="created_at", enum={"created_at", "dt_post", "vchr_titulo", "vchr_autor"}, example="created_at")
     *     ),
     *     @OA\Parameter(
     *         name="orderDirection",
     *         in="query",
     *         description="Direção da ordenação (opções: asc, desc. Padrão: desc)",
     *         required=false,
     *         @OA\Schema(type="string", default="desc", enum={"asc", "desc"}, example="desc")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista paginada de matérias da categoria retornada com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="current_page", type="integer", example=1),
     *             @OA\Property(property="per_page", type="integer", example=10),
     *             @OA\Property(property="total", type="integer", example=45),
     *             @OA\Property(property="last_page", type="integer", example=5),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="dt_post", type="string", format="date-time", example="2026-01-16T10:30:00Z"),
     *                     @OA\Property(property="vchr_autor", type="string", example="João Silva"),
     *                     @OA\Property(property="int_autor", type="integer", example=5),
     *                     @OA\Property(property="vchr_titulo", type="string", example="Título da Matéria"),
     *                     @OA\Property(property="vchr_lide", type="string", example="Lide da matéria"),
     *                     @OA\Property(property="vchr_area", type="string", example="Tecnologia"),
     *                     @OA\Property(property="id_area", type="integer", example=1),
     *                     @OA\Property(property="vchr_tags", type="string", example="tag1,tag2,tag3"),
     *                     @OA\Property(property="vchr_LinkTitulo", type="string", example="titulo-da-materia"),
     *                     @OA\Property(property="bool_onLine", type="boolean", example=true),
     *                     @OA\Property(property="bool_home", type="boolean", example=false),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2026-01-16T10:30:00Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2026-01-16T11:00:00Z"),
     *                     @OA\Property(
     *                         property="images",
     *                         type="array",
     *                         description="Imagens filtradas pelo tipo 'Top_Materia'",
     *                         @OA\Items(
     *                             type="object",
     *                             @OA\Property(property="int_Id", type="integer", example=1),
     *                             @OA\Property(property="int_MateriaId", type="integer", example=1),
     *                             @OA\Property(property="vchr_Tipo", type="string", example="Top_Materia"),
     *                             @OA\Property(property="vchr_HRef", type="string", example="https://example.com/image.jpg")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Parâmetro obrigatório ausente",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="O parâmetro id_area é obrigatório.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Could not retrieve materias by category.")
     *         )
     *     )
     * )
     */
    public function materiasCategoria(Request $request)
    {
        try {
            // Validar parâmetro obrigatório
            $idArea = $request->query('id_area');
            if (!$idArea) {
                return response()->json(['error' => 'O parâmetro id_area é obrigatório.'], 400);
            }

            // Parâmetros opcionais
            $perPage = $request->query('perPage', 10);
            $orderBy = $request->query('orderBy', 'created_at');
            $orderDirection = $request->query('orderDirection', 'desc');

            $materias = $this->materiaService->getMateriasCategoria(
                (int) $idArea,
                (int) $perPage,
                $orderBy,
                $orderDirection
            );
            
            return response()->json($materias, 200);
        } catch (Exception $e) {
            Log::error('Error retrieving materias by category: ' . $e->getMessage());
            return response()->json(['error' => 'Could not retrieve materias by category.'], 500);
        }
    }

    public function getByLinkTitulo(string $linkTitulo)
    {
        try {
            $materia = $this->materiaService->getByLinkTitulo($linkTitulo);
            
            if (!$materia) {
                return response()->json(['error' => 'Materia not found.'], 404);
            }
            
            return response()->json($materia, 200);
        } catch (Exception $e) {
            Log::error('Error retrieving materia by LinkTitulo: ' . $e->getMessage());
            return response()->json(['error' => 'Could not retrieve materia.'], 500);
        }
    }

    public function getByTag(string $tag)
    {
        try {
            $limit = request()->query('limit', 3);
            $materias = $this->materiaService->getByTag($tag, (int) $limit);
            
            return response()->json($materias, 200);
        } catch (Exception $e) {
            Log::error('Error retrieving materias by tag: ' . $e->getMessage());
            return response()->json(['error' => 'Could not retrieve materias by tag.'], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/materias/tags/summary",
     *     tags={"Matérias"},
     *     summary="Resumo das tags mais usadas",
     *     description="Retorna um resumo das tags mais utilizadas nas últimas matérias publicadas",
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Número de matérias a analisar (padrão: 50)",
     *         required=false,
     *         @OA\Schema(type="integer", default=50)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Resumo de tags retornado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="tag", type="string", example="Marvel"),
     *                     @OA\Property(property="count", type="integer", example=15)
     *                 )
     *             ),
     *             @OA\Property(property="total_tags", type="integer", example=25),
     *             @OA\Property(property="materias_analyzed", type="integer", example=50)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
    public function getTagsSummary(Request $request)
    {
        try {
            $limit = $request->query('limit', 50);
            $tagsSummary = $this->materiaService->getTagsSummary((int) $limit);
            
            return response()->json([
                'success' => true,
                'data' => $tagsSummary,
                'total_tags' => count($tagsSummary),
                'materias_analyzed' => $limit
            ], 200);
        } catch (Exception $e) {
            Log::error('Error retrieving tags summary: ' . $e->getMessage());
            return response()->json(['error' => 'Could not retrieve tags summary.'], 500);
        }
    }
}
