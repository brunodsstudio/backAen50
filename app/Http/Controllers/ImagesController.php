<?php

namespace App\Http\Controllers;

use App\Models\Images;
use App\Models\Materia;
use App\Services\ImagesService;
use App\Repositories\ImagesRepository;
use App\DTOs\ImagemDto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OA;

class ImagesController extends Controller
{
    protected ImagesService $imagesService;

    public function __construct()
    {
        $this->imagesService = new ImagesService(new ImagesRepository());
    }

    // Lista imagens de uma matéria
    public function index(Request $request, $materiaId)
    {
        try {
            Materia::findOrFail($materiaId);
            $context = $request->query('context');
            $tiposParam = $request->query('tipos');
            
            if ($context) {
                $images = $this->imagesService->getImagesByContext($materiaId, $context);
            } elseif ($tiposParam) {
                $tipos = explode(',', $tiposParam);
                $images = $this->imagesService->getAllByMateria($materiaId, $tipos);
            } else {
                $images = $this->imagesService->getAllByMateria($materiaId);
            }

            return response()->json($images, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Materia not found.'], 404);
        } catch (Exception $e) {
            Log::error('Error retrieving images: ' . $e->getMessage());
            return response()->json(['error' => 'Could not retrieve images.'], 500);
        }
    }

    // Detalhes de uma imagem
    public function show($id)
    {
        try {
            $image = $this->imagesService->getById($id);
            return response()->json($image, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Image not found.'], 404);
        }
    }

    // Adiciona uma imagem a uma matéria
    public function store(Request $request, $materiaId)
    {
        $validator = Validator::make($request->all(), [
            'vchr_ImgLink' => 'required|string|max:500',
            'vchr_ImgThumbLink' => 'nullable|string|max:500',
            'vchr_Tipo' => 'required|string|in:' . implode(',', Images::TIPOS_VALIDOS),
            'vchr_Descricao' => 'nullable|string',
            'vchr_HRef' => 'nullable|string|max:500',
            'dl_SizeW' => 'nullable|numeric',
            'dl_SizeH' => 'nullable|numeric',
            'dl_Thumb_SizeW' => 'nullable|numeric',
            'dl_Thumb_SizeH' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            Materia::findOrFail($materiaId);

            $imageDTO = new ImagemDto(
                int_Id: 0,
                vchr_ImgLink: $request->input('vchr_ImgLink'),
                vchr_ImgThumbLink: $request->input('vchr_ImgThumbLink'),
                int_MateriaId: $materiaId,
                vchr_Tipo: $request->input('vchr_Tipo'),
                vchr_Descricao: $request->input('vchr_Descricao'),
                dt_Upload: now()->toDateTimeString(),
                vchr_HRef: $request->input('vchr_HRef'),
                dl_SizeW: $request->input('dl_SizeW'),
                dl_SizeH: $request->input('dl_SizeH'),
                dl_Thumb_SizeW: $request->input('dl_Thumb_SizeW'),
                dl_Thumb_SizeH: $request->input('dl_Thumb_SizeH'),
                int_Ordem: 0
            );

            $image = $this->imagesService->create($materiaId, $imageDTO);
            return response()->json($image, 201);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Materia not found.'], 404);
        } catch (Exception $e) {
            Log::error('Error creating image: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Adiciona múltiplas imagens a uma matéria
    public function storeBatch(Request $request, $materiaId)
    {
        $validator = Validator::make($request->all(), [
            'images' => 'required|array|min:1',
            'images.*.vchr_ImgLink' => 'required|string|max:500',
            'images.*.vchr_ImgThumbLink' => 'nullable|string|max:500',
            'images.*.vchr_Tipo' => 'required|string|in:' . implode(',', Images::TIPOS_VALIDOS),
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            Materia::findOrFail($materiaId);
            $imagesDTOs = [];
            
            foreach ($request->input('images') as $imageData) {
                $imagesDTOs[] = new ImagemDto(
                    int_Id: 0,
                    vchr_ImgLink: $imageData['vchr_ImgLink'],
                    vchr_ImgThumbLink: $imageData['vchr_ImgThumbLink'] ?? null,
                    int_MateriaId: $materiaId,
                    vchr_Tipo: $imageData['vchr_Tipo'],
                    vchr_Descricao: $imageData['vchr_Descricao'] ?? null,
                    dt_Upload: now()->toDateTimeString(),
                    vchr_HRef: $imageData['vchr_HRef'] ?? null,
                    dl_SizeW: $imageData['dl_SizeW'] ?? null,
                    dl_SizeH: $imageData['dl_SizeH'] ?? null,
                    dl_Thumb_SizeW: $imageData['dl_Thumb_SizeW'] ?? null,
                    dl_Thumb_SizeH: $imageData['dl_Thumb_SizeH'] ?? null,
                    int_Ordem: 0
                );
            }

            $images = $this->imagesService->createBatch($materiaId, $imagesDTOs);
            return response()->json($images, 201);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Materia not found.'], 404);
        } catch (Exception $e) {
            Log::error('Error creating batch images: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Atualiza uma imagem
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'vchr_ImgLink' => 'nullable|string|max:500',
            'vchr_ImgThumbLink' => 'nullable|string|max:500',
            'vchr_Tipo' => 'nullable|string|in:' . implode(',', Images::TIPOS_VALIDOS),
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $existingImage = $this->imagesService->getById($id);

            $imageDTO = new ImagemDto(
                int_Id: $id,
                vchr_ImgLink: $request->input('vchr_ImgLink', $existingImage->vchr_ImgLink),
                vchr_ImgThumbLink: $request->input('vchr_ImgThumbLink', $existingImage->vchr_ImgThumbLink),
                int_MateriaId: $existingImage->int_MateriaId,
                vchr_Tipo: $request->input('vchr_Tipo', $existingImage->vchr_Tipo),
                vchr_Descricao: $request->input('vchr_Descricao', $existingImage->vchr_Descricao),
                dt_Upload: $existingImage->dt_Upload,
                vchr_HRef: $request->input('vchr_HRef', $existingImage->vchr_HRef),
                dl_SizeW: $request->input('dl_SizeW', $existingImage->dl_SizeW),
                dl_SizeH: $request->input('dl_SizeH', $existingImage->dl_SizeH),
                dl_Thumb_SizeW: $request->input('dl_Thumb_SizeW', $existingImage->dl_Thumb_SizeW),
                dl_Thumb_SizeH: $request->input('dl_Thumb_SizeH', $existingImage->dl_Thumb_SizeH),
                int_Ordem: $existingImage->int_Ordem
            );

            $image = $this->imagesService->update($id, $imageDTO);
            return response()->json($image, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Image not found.'], 404);
        } catch (Exception $e) {
            Log::error('Error updating image: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Remove uma imagem
    public function destroy($id)
    {
        try {
            $this->imagesService->delete($id);
            return response()->json(null, 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Image not found.'], 404);
        } catch (Exception $e) {
            Log::error('Error deleting image: ' . $e->getMessage());
            return response()->json(['error' => 'Could not delete image.'], 500);
        }
    }

    // Remove múltiplas imagens por tipo
    public function deleteBatch(Request $request, $materiaId)
    {
        $validator = Validator::make($request->all(), [
            'tipos' => 'required|array|min:1',
            'tipos.*' => 'required|string|in:' . implode(',', Images::TIPOS_VALIDOS),
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            Materia::findOrFail($materiaId);
            $tipos = $request->input('tipos');
            $deletedCount = $this->imagesService->deleteBatchByTipos($materiaId, $tipos);

            return response()->json([
                'message' => "$deletedCount imagens deletadas com sucesso",
                'deleted_count' => $deletedCount
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Materia not found.'], 404);
        } catch (Exception $e) {
            Log::error('Error deleting batch images: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
