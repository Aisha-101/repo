<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Kategorija;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Knygų rekomendacijos API",
 *      description="API darbui su kategorijomis, knygomis ir rekomendacijomis"
 * )
 *
 * @OA\Tag(
 *     name="Categories",
 *     description="API for managing categories"
 * )

 * @OA\Schema(
 *     schema="Kategorija",
 *     type="object",
 *     title="Category",
 *     required={"pavadinimas"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="pavadinimas", type="string", example="Nauja kategorija")
 * )
 */
class KategorijaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/kategorijos",
     *     tags={"Categories"},
     *     summary="Get all categories",
     *     @OA\Response(response=200, description="Successful request")
     * )
     */
    public function index() {
        return response()->json(Kategorija::all(), 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * @OA\Post(
     *     path="/api/kategorijos",
     *     tags={"Categories"},
     *     summary="Create a new category",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"pavadinimas"},
     *                 @OA\Property(
     *                     property="pavadinimas",
     *                     type="string",
     *                     example="Nauja kategorija"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Kategorija")
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request) {
        $validated = $request->validate([
            'pavadinimas' => 'required|string|max:255|unique:kategorijas,pavadinimas'
        ]);
        
        $kategorija = Kategorija::create($validated);
        return response()->json($kategorija, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/kategorijos/{id}",
     *     tags={"Categories"},
     *     summary="Get a single category",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Successful request"),
     *     @OA\Response(response=404, description="Category not found")
     * )
     */
    public function show($id) {
        $kat = Kategorija::find($id);
        if (!$kat) return response()->json(['message' => 'Nerasta'], 404);
        return response()->json($kat, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/kategorijos/{id}",
     *     tags={"Categories"},
     *     summary="Update a category",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"pavadinimas"},
     *             @OA\Property(property="pavadinimas", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Updated successfully"),
     *     @OA\Response(response=404, description="Category not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(Request $request, $id) {
        $kat = Kategorija::find($id);
        if (!$kat) return response()->json(['message' => 'Nerasta'], 404);
        
        $validated = $request->validate([
            'pavadinimas' => 'required|string|max:255|unique:kategorijas,pavadinimas,' . $id
        ]);
        
        $kat->update($validated);
        return response()->json($kat, 200);
    }

    /**
     * @OA\Patch(
     *     path="/api/kategorijos/{id}",
     *     tags={"Categories"},
     *     summary="Partially update a category",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="pavadinimas", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Updated successfully"),
     *     @OA\Response(response=404, description="Category not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function patch(Request $request, $id) {
        $kat = Kategorija::find($id);
        if (!$kat) return response()->json(['message' => 'Nerasta'], 404);
        
        $validated = $request->validate([
            'pavadinimas' => 'sometimes|string|max:255|unique:kategorijas,pavadinimas,' . $id
        ]);
        
        $kat->update($validated);
        return response()->json($kat, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/kategorijos/{id}",
     *     tags={"Categories"},
     *     summary="Delete a category",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Deleted successfully"),
     *     @OA\Response(response=404, description="Category not found")
     * )
     */
    public function destroy($id) {
        $kat = Kategorija::find($id);
        if (!$kat) return response()->json(['message' => 'Nerasta'], 404);
        
        // Check if category has books before deleting
        if ($kat->knygos()->count() > 0) {
            return response()->json([
                'message' => 'Negalima ištrinti kategorijos, kuri turi knygų'
            ], 422);
        }
        
        $kat->delete();
        return response()->json(null, 204);
    }
}