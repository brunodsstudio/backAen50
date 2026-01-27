<?php

namespace App\Http\Controllers;

use App\Helpers\PaginacaoHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Exception;
use Illuminate\Support\Facades\Log;

class GaleriaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/Galerias/{PastaS3}/{Pagina}",
     *     tags={"Galeria"},
     *     summary="Buscar imagens do S3 por pasta",
     *     description="Retorna todas as imagens salvas em uma pasta específica do bucket S3 da AWS com paginação",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="PastaS3",
     *         in="path",
     *         description="Nome da pasta no S3 (ex: evento-cosplay-2024)",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="Pagina",
     *         in="path",
     *         description="Número da página",
     *         required=true,
     *         @OA\Schema(type="integer", minimum=1, default=1)
     *     ),
     *     @OA\Parameter(
     *         name="quantidadePorPagina",
     *         in="query",
     *         description="Quantidade de itens por página",
     *         required=false,
     *         @OA\Schema(type="integer", minimum=1, maximum=100, default=25)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de imagens retornada com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="foto", type="string", example="https://aeranerd.s3.sa-east-1.amazonaws.com/images/galerias/evento-cosplay-2024/foto001.jpg")
     *                 )
     *             ),
     *             @OA\Property(property="pagination", type="object",
     *                 @OA\Property(property="pagina_atual", type="integer", example=1),
     *                 @OA\Property(property="itens_por_pagina", type="integer", example=25),
     *                 @OA\Property(property="total_itens", type="integer", example=150),
     *                 @OA\Property(property="total_paginas", type="integer", example=6)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autenticado - Token inválido ou ausente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pasta não encontrada no S3",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Nenhuma imagem encontrada na pasta especificada")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Parâmetros inválidos",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Parâmetros inválidos")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno do servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erro ao buscar imagens do S3")
     *         )
     *     )
     * )
     */
    public function fetchGaleria(Request $request, string $pastaS3, int $pagina = 1)
    {
        try {
            // Validar parâmetros
            if ($pagina < 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'O número da página deve ser maior ou igual a 1'
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            // Obter quantidade por página (padrão: 25)
            $quantidadePorPagina = $request->query('quantidadePorPagina', 25);
            
            // Validar quantidade por página
            if ($quantidadePorPagina < 1 || $quantidadePorPagina > 100) {
                return response()->json([
                    'success' => false,
                    'message' => 'A quantidade por página deve estar entre 1 e 100'
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            // Buscar todos os arquivos da pasta no S3
            $caminhoCompleto = 'images/galerias/' . $pastaS3 . '/';
            
            // Verificar se o disco S3 está configurado
            if (!Storage::disk('s3')->exists($caminhoCompleto)) {
                Log::warning("Pasta não encontrada no S3: {$caminhoCompleto}");
            }
            
            $todosArquivos = Storage::disk('s3')->allFiles($caminhoCompleto);

            // Verificar se há arquivos
            if (empty($todosArquivos)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nenhuma imagem encontrada na pasta especificada'
                ], Response::HTTP_NOT_FOUND);
            }

            // Filtrar apenas imagens (jpg, jpeg, png, gif, webp)
            $imagensValidas = array_filter($todosArquivos, function($arquivo) {
                $extensao = strtolower(pathinfo($arquivo, PATHINFO_EXTENSION));
                return in_array($extensao, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
            });

            // Reindexar array
            $imagensValidas = array_values($imagensValidas);
            $totalItens = count($imagensValidas);
            
            // Paginar os resultados
            $imagensPaginadas = PaginacaoHelper::paginar($pagina, $quantidadePorPagina, $imagensValidas);

            // Montar URLs completas das imagens
            $resultado = [];
            $regiao = env('AWS_DEFAULT_REGION', 'sa-east-1');
            $bucket = env('AWS_BUCKET', 'aeranerd');
            
            foreach ($imagensPaginadas as $imagem) {
                $resultado[] = [
                    'foto' => "https://{$bucket}.s3.{$regiao}.amazonaws.com/{$imagem}"
                ];
            }

            // Calcular total de páginas
            $totalPaginas = ceil($totalItens / $quantidadePorPagina);

            // Retornar resposta com dados e informações de paginação
            return response()->json([
                'success' => true,
                'data' => $resultado,
                'pagination' => [
                    'pagina_atual' => $pagina,
                    'itens_por_pagina' => $quantidadePorPagina,
                    'total_itens' => $totalItens,
                    'total_paginas' => $totalPaginas
                ]
            ], Response::HTTP_OK);

        } catch (Exception $e) {
            Log::error('Erro ao buscar galeria do S3: ' . $e->getMessage(), [
                'pasta' => $pastaS3,
                'pagina' => $pagina,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar imagens do S3',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
