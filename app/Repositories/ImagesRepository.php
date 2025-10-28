<?php

namespace App\Repositories;

use App\Interfaces\ImagesInterface;
use App\Models\Images;
use App\Models\Materia;
use App\DTOs\ImagesDto;
use App\DTOs\MateriaDto;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class ImagesRepository implements ImagesInterface{
    protected Images $model;

    public function __construct(Images $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function getAllByMateria(int $idMateria)
    {
        return $image = $this->model->whereHas('materia', function ($query) use ($idMateria) {
            $query->where('id', $idMateria);
            })->getAll();
    }

    public function getById(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(int $idMateria,ImagesDTO $imagesDTO)
    {



        try {
            $image = new Images();
            $image->vchr_ImgLink = $imagesDTO->vchr_ImgLink;
            $image->vchr_ImgThumbLink = $imagesDTO->vchr_ImgThumbLink;
            $image->int_MateriaId = $imagesDTO->int_MateriaId;
            $image->vchr_Tipo = $imagesDTO->vchr_Tipo;
            $image->vchr_Descricao = $imagesDTO->vchr_Descricao;
            $image->dt_Upload = $imagesDTO->dt_Upload;
            $image->vchr_HRef = $imagesDTO->vchr_HRef;
            $image->dl_SizeW = $imagesDTO->dl_SizeW;
            $image->dl_SizeH = $imagesDTO->dl_SizeH;
            $image->dl_Thumb_SizeW = $imagesDTO->dl_Thumb_SizeW;
            $image->dl_Thumb_SizeH = $imagesDTO->dl_Thumb_SizeH;
            $image->int_Ordem = $imagesDTO->int_Ordem;
            $image->save();

            return $image;
        } catch (Exception $e) {
            Log::error('Error creating image: ' . $e->getMessage());
            throw new Exception('Could not create image.');
        }
    }

    public function createBash(int $idMateria,ImagesDTO $imagesDTO)
    {

        try {
            foreach($imagesDTO as $imageDTO)
            {
                $image = new Images();
                $image->vchr_ImgLink = $imageDTO->vchr_ImgLink;
                $image->vchr_ImgThumbLink = $imageDTO->vchr_ImgThumbLink;
                $image->int_MateriaId = $imageDTO->int_MateriaId;
                $image->vchr_Tipo = $imageDTO->vchr_Tipo;
                $image->vchr_Descricao = $imageDTO->vchr_Descricao;
                $image->dt_Upload = $imageDTO->dt_Upload;
                $image->vchr_HRef = $imageDTO->vchr_HRef;
                $image->dl_SizeW = $imageDTO->dl_SizeW;
                $image->dl_SizeH = $imageDTO->dl_SizeH;
                $image->dl_Thumb_SizeW = $imageDTO->dl_Thumb_SizeW;
                $image->dl_Thumb_SizeH = $imageDTO->dl_Thumb_SizeH;
                $image->int_Ordem = $imageDTO->int_Ordem;
                $image->save();
            }
           

            return $image;
        } catch (Exception $e) {
            Log::error('Error creating image: ' . $e->getMessage());
            throw new Exception('Could not create image.');
        }
    }

    public function update(int $id, ImagesDTO $imagesDTO)
    {
        try {
         $image = $this->model->whereHas('materia', function ($query) use ($id) {
            $query->where('id', $id);
            })->firstOrFail()->update([
            'vchr_ImgLink' => $imagesDTO->vchr_ImgLink,    
            'vchr_ImgThumbLink' => $imagesDTO->vchr_ImgThumbLink,
            'int_MateriaId' => $imagesDTO->int_MateriaId,
            'vchr_Tipo' => $imagesDTO->vchr_Tipo,
            'vchr_Descricao' => $imagesDTO->vchr_Descricao,
            'dt_Upload' => $imagesDTO->dt_Upload,
            'vchr_HRef' => $imagesDTO->vchr_HRef,
            'dl_SizeW' => $imagesDTO->dl_SizeW,
            'dl_SizeH' => $imagesDTO->dl_SizeH,
            'dl_Thumb_SizeW' => $imagesDTO->dl_Thumb_SizeW,
            'dl_Thumb_SizeH' => $imagesDTO->dl_Thumb_SizeH,
            'int_Ordem' => $imagesDTO->int_Ordem,
        ]);

            return $image;
        } catch (Exception $e) {
            Log::error('Error updating image: ' . $e->getMessage());
            throw new Exception('Could not update image.');
        }
    }

    public function delete(int $id)
    {
        try {

            $image = $this->model->whereHas('materia', function ($query) use ($id) {
            $query->where('id', $id);
            })->getAll()->delete();

            return true;
        } catch (ModelNotFoundException $e) {
            Log::error('Error deleting image: ' . $e->getMessage());
            throw new Exception('Image not found.');
        } catch (Exception $e) {
            Log::error('Error deleting image: ' . $e->getMessage());
            throw new Exception('Could not delete image.');
        }
    }
}
