<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Rekomendacija;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Recommendations",
 *     description="API for managing recommendations"
 * )
 * @OA\Schema(
 *     schema="Rekomendacija",
 *     type="object",
 *     required={"knyga_id","naudotojas","ivertinimas"},
 *     @OA\Property(property="id", type="integer", example=3),
 *     @OA\Property(property="knyga_id", type="integer", example=5),
 *     @OA\Property(property="naudotojas", type="string", example="Mantas"),
 *     @OA\Property(property="komentaras", type="string", example="Klasika, verta perskaityti kiekvienam."),
 *     @OA\Property(property="ivertinimas", type="integer", example=5),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(
 *         property="knyga",
 *         ref="#/components/schemas/Knyga"
 *     )
 * )
 */
class RekomendacijaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/rekomendacijos",
     *     tags={"Recommendations"},
     *     summary="Get all recommendations",
     *     @OA\Response(response=200, description="Successful request")
     * )
     */
    public function index() {
        $rekomendacijos = Rekomendacija::with('knyga')->get();
        return response()->json($rekomendacijos, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * @OA\Post(
     *     path="/api/rekomendacijos",
     *     tags={"Recommendations"},
     *     summary="Create a new recommendation",
     *     description="Create a new recommendation for a book",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"knyga_id","naudotojas","komentaras","ivertinimas"},
     *             @OA\Property(property="knyga_id", type="integer", example=2),
     *             @OA\Property(property="naudotojas", type="string", example="Mantas"),
     *             @OA\Property(property="komentaras", type="string", example="Klasika, verta perskaityti kiekvienam."),
     *             @OA\Property(property="ivertinimas", type="integer", example=5, minimum=1, maximum=5)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Rekomendacija")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The knyga id field is required.")
     *         )
     *     )
     * )
     */
    public function store(Request $request) {
        $validated = $request->validate([
            'knyga_id' => 'required|exists:knygos,id',
            'naudotojas' => 'required|string|max:255',
            'komentaras' => 'required|string',
            'ivertinimas' => 'required|integer|min:1|max:5'
        ]);
        
        $rek = Rekomendacija::create($validated);
        $rek->load('knyga');
        return response()->json($rek, 201);
    }


    /**
     * @OA\Get(
     *     path="/api/rekomendacijos/{id}",
     *     tags={"Recommendations"},
     *     summary="Get a single recommendation",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Successful request"),
     *     @OA\Response(response=404, description="Recommendation not found")
     * )
     */
    public function show($id) {
        $rek = Rekomendacija::with('knyga')->find($id);
        if (!$rek) return response()->json(['message' => 'Nerasta'], 404);
        return response()->json($rek, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/rekomendacijos/{id}",
     *     tags={"Recommendations"},
     *     summary="Update a recommendation",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="knyga_id", type="integer"),
     *             @OA\Property(property="naudotojas", type="string"),
     *             @OA\Property(property="komentaras", type="string"),
     *             @OA\Property(property="ivertinimas", type="integer", minimum=1, maximum=5)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Updated successfully"),
     *     @OA\Response(response=404, description="Recommendation not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(Request $request, $id) {
        $rek = Rekomendacija::find($id);
        if (!$rek) return response()->json(['message' => 'Nerasta'], 404);
        
        $validated = $request->validate([
            'knyga_id' => 'sometimes|required|exists:knygos,id',
            'naudotojas' => 'sometimes|required|string|max:255',
            'komentaras' => 'sometimes|required|string',
            'ivertinimas' => 'sometimes|required|integer|min:1|max:5'
        ]);
        
        $rek->update($validated);
        $rek->load('knyga');
        return response()->json($rek, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/rekomendacijos/{id}",
     *     tags={"Recommendations"},
     *     summary="Delete a recommendation",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Deleted successfully"),
     *     @OA\Response(response=404, description="Recommendation not found")
     * )
     */
    public function destroy($id) {
        $rek = Rekomendacija::find($id);
        if (!$rek) return response()->json(['message' => 'Nerasta'], 404);
        
        $rek->delete();
        return response()->json(null, 204);
    }
}  