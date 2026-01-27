<?php

namespace App\Http\Controllers;

use App\Models\Writers;
use App\Http\Resources\WritersResource;
use Illuminate\Http\Request;
use App\Services\WritersService;
use App\Repositories\WritersRepository;
use App\DTOs\WritersDto;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OA;

class WritersController extends Controller
{
    protected WritersService $writersService;

    public function __construct()
    {
        $this->writersService = new WritersService(new WritersRepository());
    }

    /**
     * @OA\Get(
     *     path="/api/writers",
     *     tags={"Writers"},
     *     summary="Listar todos os escritores",
     *     description="Retorna uma lista paginada de todos os escritores ordenados alfabeticamente",
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
     *         description="Lista de escritores retornada com sucesso",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Writer")
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
            
            $writers = $this->writersService->getAllWithPaginate($perPage, $page);
            
            return response()->json(WritersResource::collection($writers->items()), 200);
        } catch (Exception $e) {
            Log::error('Error retrieving writers: ' . $e->getMessage());
            return response()->json(['error' => 'Could not retrieve writers.'], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/writers",
     *     tags={"Writers"},
     *     summary="Criar um novo escritor",
     *     description="Cria um novo escritor no sistema",
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"vchr_Nome", "vchr_Nick"},
     *             @OA\Property(property="vchr_Nome", type="string", maxLength=100, description="Nome completo do escritor", example="João Silva"),
     *             @OA\Property(property="vchr_Nick", type="string", maxLength=50, description="Apelido/Nick do escritor", example="joaosilva"),
     *             @OA\Property(property="long_Card", type="string", description="Biografia do escritor", example="Escritor e jornalista especializado em cultura pop"),
     *             @OA\Property(property="bool_Enable", type="boolean", description="Status de habilitação", example=true),
     *             @OA\Property(property="vchr_LinkFoto", type="string", maxLength=50, description="Link da foto do escritor", example="foto-joao.jpg"),
     *             @OA\Property(property="vchr_LinkInta", type="string", maxLength=50, description="Link do Instagram", example="@joaosilva"),
     *             @OA\Property(property="vchr_Cargo", type="string", maxLength=50, description="Cargo/Função", example="Editor")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Escritor criado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Writer")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Dados de validação inválidos",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vchr_Nome' => 'required|string|max:100',
            'vchr_Nick' => 'required|string|max:50|unique:tb_aen_writers,vchr_Nick',
            'long_Card' => 'nullable|string',
            'bool_Enable' => 'nullable|boolean',
            'vchr_LinkFoto' => 'nullable|string|max:50',
            'vchr_LinkInta' => 'nullable|string|max:50',
            'vchr_Cargo' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $writersDTO = new WritersDto(
                int_Id: 0,
                vchr_Nome: $request->input('vchr_Nome'),
                vchr_Nick: $request->input('vchr_Nick'),
                long_Card: $request->input('long_Card'),
                bool_Enable: $request->input('bool_Enable', true),
                vchr_LinkFoto: $request->input('vchr_LinkFoto'),
                vchr_LinkInta: $request->input('vchr_LinkInta'),
                vchr_Cargo: $request->input('vchr_Cargo')
            );

            $writer = $this->writersService->create($writersDTO);
            return response()->json(new WritersResource($writer), 201);
        } catch (Exception $e) {
            Log::error('Error creating writer: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/writers/{id}",
     *     tags={"Writers"},
     *     summary="Obter detalhes de um escritor",
     *     description="Retorna os detalhes de um escritor específico",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do escritor",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes do escritor",
     *         @OA\JsonContent(ref="#/components/schemas/Writer")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Escritor não encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Writer not found.")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $writer = $this->writersService->getById($id);
            return response()->json(new WritersResource($writer), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Writer not found.'], 404);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/writers/{id}",
     *     tags={"Writers"},
     *     summary="Atualizar um escritor",
     *     description="Atualiza os dados de um escritor existente",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do escritor",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="vchr_Nome", type="string", maxLength=100, description="Nome completo do escritor"),
     *             @OA\Property(property="vchr_Nick", type="string", maxLength=50, description="Apelido/Nick do escritor"),
     *             @OA\Property(property="long_Card", type="string", description="Biografia do escritor"),
     *             @OA\Property(property="bool_Enable", type="boolean", description="Status de habilitação"),
     *             @OA\Property(property="vchr_LinkFoto", type="string", maxLength=50, description="Link da foto do escritor"),
     *             @OA\Property(property="vchr_LinkInta", type="string", maxLength=50, description="Link do Instagram"),
     *             @OA\Property(property="vchr_Cargo", type="string", maxLength=50, description="Cargo/Função")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Escritor atualizado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Writer")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Escritor não encontrado"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Dados de validação inválidos"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'vchr_Nome' => 'sometimes|required|string|max:100',
            'vchr_Nick' => 'sometimes|required|string|max:50|unique:tb_aen_writers,vchr_Nick,' . $id . ',int_Id',
            'long_Card' => 'nullable|string',
            'bool_Enable' => 'nullable|boolean',
            'vchr_LinkFoto' => 'nullable|string|max:50',
            'vchr_LinkInta' => 'nullable|string|max:50',
            'vchr_Cargo' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $existingWriter = $this->writersService->getById($id);

            $writersDTO = new WritersDto(
                int_Id: $id,
                vchr_Nome: $request->input('vchr_Nome', $existingWriter->vchr_Nome),
                vchr_Nick: $request->input('vchr_Nick', $existingWriter->vchr_Nick),
                long_Card: $request->input('long_Card', $existingWriter->long_Card),
                bool_Enable: $request->input('bool_Enable', $existingWriter->bool_Enable),
                vchr_LinkFoto: $request->input('vchr_LinkFoto', $existingWriter->vchr_LinkFoto),
                vchr_LinkInta: $request->input('vchr_LinkInta', $existingWriter->vchr_LinkInta),
                vchr_Cargo: $request->input('vchr_Cargo', $existingWriter->vchr_Cargo)
            );

            $writer = $this->writersService->update($id, $writersDTO);
            return response()->json(new WritersResource($writer), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Writer not found.'], 404);
        } catch (Exception $e) {
            Log::error('Error updating writer: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/writers/{id}",
     *     tags={"Writers"},
     *     summary="Remover um escritor",
     *     description="Remove um escritor do sistema",
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do escritor",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Escritor removido com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Escritor não encontrado"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao remover escritor"
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            $this->writersService->delete($id);
            return response()->json(null, 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Writer not found.'], 404);
        } catch (Exception $e) {
            Log::error('Error deleting writer: ' . $e->getMessage());
            return response()->json(['error' => 'Could not delete writer.'], 500);
        }
    }
}
