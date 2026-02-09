<?php

namespace App\Repositories;

use App\Interfaces\ImagesInterface;
use App\Models\Images;
use App\DTOs\ImagemDto;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class ImagesRepository implements ImagesInterface
{
    protected Images $model;

    public function __construct(Images $model = null)
    {
        $this->model = $model ?? new Images();
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function getAllByMateria(int $idMateria, ?array $tipos = null)
    {
        $query = $this->model->where('int_MateriaId', $idMateria);
        
        if ($tipos) {
            $query->whereIn('vchr_Tipo', $tipos);
        }
        
        return $query->get();
    }

    public function getById(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(int $idMateria, ImagemDto $imagesDTO)
    {
        try {
            $image = new Images();
            $image->vchr_ImgLink = $imagesDTO->vchr_ImgLink;
            $image->vchr_ImgThumbLink = $imagesDTO->vchr_ImgThumbLink;
            $image->int_MateriaId = $idMateria;
            $image->vchr_Tipo = $imagesDTO->vchr_Tipo;
            $image->vchr_Descricao = $imagesDTO->vchr_Descricao;
            $image->dt_Upload = $imagesDTO->dt_Upload ?? now();
            $image->vchr_HRef = $imagesDTO->vchr_HRef;
            $image->dl_SizeW = $imagesDTO->dl_SizeW;
            $image->dl_SizeH = $imagesDTO->dl_SizeH;
            $image->dl_Thumb_SizeW = $imagesDTO->dl_Thumb_SizeW;
            $image->dl_Thumb_SizeH = $imagesDTO->dl_Thumb_SizeH;
            $image->int_Ordem = $imagesDTO->int_Ordem ?? 0;
            $image->save();

            return $image;
        } catch (Exception $e) {
            Log::error('Error creating image: ' . $e->getMessage());
            throw new Exception('Could not create image.');
        }
    }

    public function createBatch(int $idMateria, array $imagesDTOs)
    {
        try {
            $createdImages = [];
            
            DB::beginTransaction();
            
            foreach ($imagesDTOs as $imageDTO) {
                $image = new Images();
                $image->vchr_ImgLink = $imageDTO->vchr_ImgLink;
                $image->vchr_ImgThumbLink = $imageDTO->vchr_ImgThumbLink;
                $image->int_MateriaId = $idMateria;
                $image->vchr_Tipo = $imageDTO->vchr_Tipo;
                $image->vchr_Descricao = $imageDTO->vchr_Descricao;
                $image->dt_Upload = $imageDTO->dt_Upload ?? now();
                $image->vchr_HRef = $imageDTO->vchr_HRef;
                $image->dl_SizeW = $imageDTO->dl_SizeW;
                $image->dl_SizeH = $imageDTO->dl_SizeH;
                $image->dl_Thumb_SizeW = $imageDTO->dl_Thumb_SizeW;
                $image->dl_Thumb_SizeH = $imageDTO->dl_Thumb_SizeH;
                $image->int_Ordem = $imageDTO->int_Ordem ?? 0;
                $image->save();
                
                $createdImages[] = $image;
            }
            
            DB::commit();
            
            return $createdImages;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating batch images: ' . $e->getMessage());
            throw new Exception('Could not create batch images.');
        }
    }

    public function update(int $id, ImagemDto $imagesDTO)
    {
        try {
            $image = $this->model->findOrFail($id);
            
            $image->vchr_ImgLink = $imagesDTO->vchr_ImgLink ?? $image->vchr_ImgLink;
            $image->vchr_ImgThumbLink = $imagesDTO->vchr_ImgThumbLink ?? $image->vchr_ImgThumbLink;
            $image->vchr_Tipo = $imagesDTO->vchr_Tipo ?? $image->vchr_Tipo;
            $image->vchr_Descricao = $imagesDTO->vchr_Descricao ?? $image->vchr_Descricao;
            $image->vchr_HRef = $imagesDTO->vchr_HRef ?? $image->vchr_HRef;
            $image->dl_SizeW = $imagesDTO->dl_SizeW ?? $image->dl_SizeW;
            $image->dl_SizeH = $imagesDTO->dl_SizeH ?? $image->dl_SizeH;
            $image->dl_Thumb_SizeW = $imagesDTO->dl_Thumb_SizeW ?? $image->dl_Thumb_SizeW;
            $image->dl_Thumb_SizeH = $imagesDTO->dl_Thumb_SizeH ?? $image->dl_Thumb_SizeH;
            $image->int_Ordem = $imagesDTO->int_Ordem ?? $image->int_Ordem;
            
            $image->save();

            return $image;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            Log::error('Error updating image: ' . $e->getMessage());
            throw new Exception('Could not update image.');
        }
    }

    public function delete(int $id)
    {
        try {
            $image = $this->model->findOrFail($id);
            $image->delete();
            
            return true;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            Log::error('Error deleting image: ' . $e->getMessage());
            throw new Exception('Could not delete image.');
        }
    }

    public function deleteBatchByTipos(int $idMateria, array $tipos)
    {
        try {
            $deletedCount = $this->model
                ->where('int_MateriaId', $idMateria)
                ->whereIn('vchr_Tipo', $tipos)
                ->delete();
            
            return $deletedCount;
        } catch (Exception $e) {
            Log::error('Error deleting batch images: ' . $e->getMessage());
            throw new Exception('Could not delete batch images.');
        }
    }
}
