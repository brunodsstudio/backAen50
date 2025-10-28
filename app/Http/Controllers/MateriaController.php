<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use App\Http\Resources\MateriaResource;
use Illuminate\Http\Request;
use App\Services\MateriaService;
use App\Repositories\MateriaRepository;

class MateriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       // try{
            $materiaService = new MateriaService(new MateriaRepository());
            $materia = $materiaService->getAllWithPaginate(10, 1);


           // dd($materia->items());
          
            return response()->json( MateriaResource::collection($materia->items()), 200);
       /* } catch (\Exception $e) {
            return  response()->json(['error' => $e->getMessage()], 500);
        }*/
    
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(materia $materia)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(materia $materia)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, materia $materia)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(materia $materia)
    {
        //
    }
}
