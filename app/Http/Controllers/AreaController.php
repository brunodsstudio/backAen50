<?php

namespace App\Http\Controllers;

use App\Models\area;
use Illuminate\Http\Request;
use App\Services\AreaService;
use App\DTOs\AreaDto;       
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AreaController extends Controller
{
    protected AreaService $areaService;

    public function __construct(AreaService $areaService)
    {
        $this->areaService = $areaService;
    }

    public function index()
    {
        try {
            $areas = $this->areaService->getAll();
            return response()->json($areas, 200);
        } catch (Exception $e) {
            Log::error('Error retrieving areas: ' . $e->getMessage());
            return response()->json(['error' => 'Could not retrieve areas.'], 500);
        }
    }

    public function show($id)
    {
        try {
            $area = $this->areaService->getById($id);
            return response()->json($area, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Area not found.'], 404);
        } catch (Exception $e) {
            Log::error('Error retrieving area: ' . $e->getMessage());
            return response()->json(['error' => 'Could not retrieve area.'], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vchr_AreaNome' => 'required|string|max:255',
            'vchr_Tag' => 'nullable|string|max:255',
            'type' => 'array',
            'type.*' => 'in:bd,pasta',
            'b_menu' => 'boolean',
            'int_rolePermission' => 'integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $areaDTO = new AreaDto(
                int_Id: 0,
                vchr_AreaNome: $request->input('vchr_AreaNome'),
                vchr_Tag: $request->input('vchr_Tag'),
                type: $request->input('type', ['bd', 'pasta']),
                b_menu: $request->input('b_menu', false),
                int_rolePermission: $request->input('int_rolePermission', 0)
            );

            $area = $this->areaService->create($areaDTO);
            return response()->json($area, 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (Exception $e) {
            Log::error('Error creating area: ' . $e->getMessage());
            return response()->json(['error' => 'Could not create area.'], 500);
        }
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'vchr_AreaNome' => 'required|string|max:255',
            'vchr_Tag' => 'nullable|string|max:255',
            'type' => 'array',
            'type.*' => 'in:bd,pasta',
            'b_menu' => 'boolean',
            'int_rolePermission' => 'integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $areaDTO = new AreaDto(
                int_Id: $id,
                vchr_AreaNome: $request->input('vchr_AreaNome'),
                vchr_Tag: $request->input('vchr_Tag'),
                type: $request->input('type', ['bd', 'pasta']),
                b_menu: $request->input('b_menu', false),
                int_rolePermission: $request->input('int_rolePermission', 0)
            );

            $area = $this->areaService->update($id, $areaDTO);
            return response()->json($area, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Area not found.'], 404);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (Exception $e) {
            Log::error('Error updating area: ' . $e->getMessage());
            return response()->json(['error' => 'Could not update area.'], 500);
        }
    }  
    public function destroy($id)
    {
        try {
            $this->areaService->delete($id);
            return response()->json(null, 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Area not found.'], 404);
        } catch (Exception $e) {
            Log::error('Error deleting area: ' . $e->getMessage());
            return response()->json(['error' => 'Could not delete area.'], 500);
        }
    } 
}  
